<?php

App::uses('Mwb/MwbAppController', 'Controller');

class MwbController extends MwbAppController {

	public $uses = array('Mwb.Mwb');
	
	public function admin_index(){
		$this->set('files', $this->Mwb->listFiles());
	}
	
	public function admin_generate($plugin = null, $file = null){
		if(
			!$plugin 
			|| !$file
			|| !($path = $this->Mwb->resolvePath($plugin.'.'.$file))
		){
			$this->Session->setFlash(__d('Mwb', 'Error : File not found'));
		}
		elseif(!($path = $this->Mwb->generate($path))){
			$this->Session->setFlash(__d('Mwb', 'Error : Unable to create schema'));
		}
		else {
			$this->Session->setFlash(__d('Mwb', 'Schema generated under %s', $path));
		}
		$this->redirect(array('action' => 'index'));
	}
	
}
