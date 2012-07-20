<?php

App::uses('Mwb/MwbAppModel', 'Model');

App::uses('Folder', 'Utility');
App::uses('Xml', 'Utility');

App::build(array('Vendor' => array(App::pluginPath('Mwb').'Vendor'.DS.'mysqlworkbenchschema'.DS)));
App::import('Vendor', 'MySQLWorkbenchXML', array('file' => 'MySQLWorkbenchXML.class.php'));
App::import('Vendor', 'MySQLWorkbench', array('file' => 'MySQLWorkbench.class.php'));

class Mwb extends MwbAppModel {
	
	public $useTable = false;
	
	public $_schema = array(
		'name' => array(
			'type' => 'string',
			'length' => 255
		)
	);
	
	public function listFiles(){
		$paths = array(
			'app' => APP.'Config'.DS.'Schema'.DS
		);
		$plugins = App::objects('plugin');
		CakePlugin::loadAll();
		foreach($plugins as $plugin){
			$paths[$plugin] = App::pluginPath($plugin).'Config'.DS.'Schema'.DS;
		}
		$fileList = array();
		$folder = new Folder();
		foreach($paths as $plugin => $path){
			if($folder->cd($path)){
				$files = $folder->find('.*\.mwb');
				if(!empty($files)){
					foreach($files as $file){
						$pathinfo = pathinfo($file);
						$fileList[] = array(
							'name' => $plugin.'/'.$pathinfo['filename']
						);
					}
				}
			}
		}
		return $fileList;
	}
	
	public function resolvePath($name = null){
		if(!$name){
			return false;
		}
		list($plugin, $filename) = pluginSplit($name);
		if($plugin == 'app') {
			$folder = APP.'Config'.DS.'Schema'.DS;
		}
		else {
			CakePlugin::load($plugin);
			$folder = App::pluginPath($plugin).'Config'.DS.'Schema'.DS;
		}
		return $folder.$filename.'.mwb';
	}
	
/**
 * @todo Manage default values + primary keys
 */
	public function generate($filePath = null){
		if(!file_exists($filePath)){
			return false;
		}
		$info = pathinfo($filePath);
		$schema = new MySQLWorkbench($info['filename'], $info['dirname']);
		$xml = $schema->noHeader()->run()->render();
		$array = Xml::toArray(Xml::build($xml));
		$tables = array();
		foreach($array['schema']['table'] as $table){
			$tables[$table['@name']] = array();
			if(!isset($table['column'][0])){
				$keys = array('type', 'isPrimary', 'auto', 'isRequired', 'size', 'index');
				$col = array();
				foreach($keys as $key){
					$col[$key] = isset($table['column']['@'.$key]) ? $table['column']['@'.$key] : null;
				}
				$tables[$table['@name']][$table['column']['@name']] = $col;
			}
			else{
				foreach($table['column'] as $_col){
					$keys = array('type', 'isPrimary', 'auto', 'isRequired', 'size', 'index');
					$col = array();
					foreach($keys as $key){
						$col[$key] = isset($_col['@'.$key]) ? $_col['@'.$key] : null;
					}
					$tables[$table['@name']][$_col['@name']] = $col;
				}
			}
		}
		
		$schemaName = Inflector::camelize($info['filename']);
		
		$types = array(
			'int' => 'integer',
			'varchar' => 'string'
		);

		$_tables = array();
		foreach($tables as $table => $fields){
			$_fields = array();
			foreach($fields as $field => $conf){
				$_conf = array();
				if(isset($conf['type']) && !empty($conf['type'])){
					if(isset($types[$conf['type']])){
						$conf['type'] = $types[$conf['type']];
					}
					$_conf[] = "'type' => '{$conf['type']}'";
				}
				if(isset($conf['size']) && !empty($conf['size']) && $conf['size'] != -1){
					$_conf[] = "'length' => {$conf['size']}";
				}
				if(!isset($conf['isRequired']) && empty($conf['isRequired'])){
					$_conf[] = "'null' => false";
				}
				else {
					$_conf[] = "'null' => true";
					$_conf[] = "'default' => null";
				}
				if(isset($conf['isPrimary']) && !empty($conf['isPrimary'])){
					$_conf[] = "'key' => 'primary'";
				}
				$_fields[] = "\t\t'$field' => array(".join(', ', $_conf).')';
			}
			$_tables[] = "\t'$table' => array(".PHP_EOL.join(','.PHP_EOL, $_fields).PHP_EOL."\t)";
		}
		$content = 
			'<?php'
			.PHP_EOL
			.PHP_EOL
			.'class '.$schemaName.'Schema extends CakeSchema {'
			.PHP_EOL
			.PHP_EOL
			.join(','.PHP_EOL, $_tables)
			.PHP_EOL
			.PHP_EOL
			.'}';
			
		$schemaPath = $info['dirname'].DS.$info['filename'].'.php';
		file_put_contents($schemaPath, $content);
		return $schemaPath;
	}
	
}
