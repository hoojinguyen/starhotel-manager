<?php

namespace Romi\Transformer;

use Romi\Domain\Tenant;
use League\Fractal\TransformerAbstract;

class AuthorizedTenantTransformer extends TransformerAbstract {

	public function transform(Tenant $tenant) {
		return [
			'name' => $tenant->getName(),
			'email' => $tenant->getEmail(),
			'token' => $tenant->getToken()
		];
	}

}
