<?php
/*
Copyright (c) 2014 Ray Smith

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
class ApplicationDataConnectionPool
{
    protected static $connections;
    protected static $pool;
    public static function init(){
        //Establish Pool
        ApplicationDataConnectionPool::$pool = array();
        //Create connections collection
        ApplicationDataConnectionPool::$connections = array();
        ApplicationDataConnectionPool::$connections['static'] = function(){
			require_once(LIBPATH."mysql_database.class.php");
			$db = new ApplicationDatabase(new MysqlDatabase("staticdb.ip","username","passwd"));
			return $db;
        };
        ApplicationDataConnectionPool::$connections['session'] = function(){
			require_once(LIBPATH."mysql_database.class.php");
			$db = new ApplicationDatabase(new MysqlDatabase("sessiondb.ip","username","passwd"));
			return $db;
        };
	}
    public static function get($name)
    {
		if( !isset(ApplicationDataConnectionPool::$pool[$name]) ){
			$connections = ApplicationDataConnectionPool::$connections;
			ApplicationDataConnectionPool::set($name, $connections[$name]());
		}
		return ApplicationDataConnectionPool::$pool[$name];
    }
    public static function set($name,$value)
    {
        ApplicationDataConnectionPool::$pool[$name] = $value;
    }
}
?>
