<?php

namespace Romi\Tests\Functional;

use Romi\Domain\Tenant;
use Romi\Tests\BaseTestCase;

class AuthenticateTest extends BaseTestCase {

	/** @test */
	public function a_tenant_can_obtain_a_jwt_token_after_log_in() {
		$tenant = new Tenant();
		$tenant->setName('Romi Technology')
				->setUsername('romi')
				->setPassword(password_hash('romiadmintest', PASSWORD_DEFAULT))
				->setEmail('admin@romi.com');

		$payload = [
			'username' => $tenant->getUsername(),
			'password' => 'romiadmintest'
		];

		$response = $this->request(self::METHOD_POST, '/api/tenant', $payload);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertArrayHasKey('token', json_decode((string) $response->getBody(), true));
	}

}
