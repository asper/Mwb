CakePHP Mwb
===========

This plugin converts Mysqlworkbench schemas into CakePHP schemas

Installation
------------

Simply download and put it in the `Mwb` folder and its content in your `app/Plugin` folder.

Load plugin in `app/Config/bootstrap.php` :

	CakePlugin::load('Mwb');

Using composer: 

    "require" : {
        "asper/mwb": "*"
    }

Usage
-----

- Create your database schema in Mysqlworkbench
- Save it in your's `Config/Schema` folder (also works with plugins, path : `Plugin/{plugin_name}/Config/Schema`)
- Navigate to url `http://{domain}/admin/mwb/index`

License
-------

The MIT License (MIT)

Copyright (c) 2013 Pierre Aboucaya

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
