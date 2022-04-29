<?php
namespace Sang\Basicfun\Common\Provider;

use Illuminate\Support\ServiceProvider;
use Sang\Basicfun\Common\CommonFun;

class CommonServiceProvider extends ServiceProvider{

	public function register(){
		$this->app->bind('commonfunc',function($app){
			return new CommonFun();
		});
	}
}