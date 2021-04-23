<?php

namespace Romi\Shared;

class TenantScopePerRequest{
	
	private static $tenantId = null;
	
	public static function applyTenantScope($tenantId){
		self::$tenantId = $tenantId;
	}
	
	public static function getTenantScope(){
		return self::$tenantId;
	}
}

