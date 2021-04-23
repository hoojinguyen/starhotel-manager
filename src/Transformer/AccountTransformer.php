<?php

namespace Romi\Transformer;

use Romi\Domain\Account;
use League\Fractal\TransformerAbstract;
use Romi\Shared\Cryptography;

class AccountTransformer extends TransformerAbstract {

	public function transform(Account $account) {
		$decryptedData = Cryptography::Decrypt($account->getData());
		return [
			'id' => $account->getId(),
			'payor_id' => $account->getProfile()->getPayorId(),
			'data' => json_decode($decryptedData),
			'type' => $account->getType()
		];
	}

}
