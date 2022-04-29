<?php
namespace Sang\Basicfun\Common\Facade;

use Illuminate\Support\Facades\Facade;

class Commonfunc extends Facade{
	protected static function getFacadeAccessor(){
		return 'commonfunc';
	}
}