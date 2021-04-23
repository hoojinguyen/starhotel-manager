<?php

namespace Romi\Transformer;

use Romi\Domain\TaxBill;
use League\Fractal\TransformerAbstract;
use Romi\Shared\Cryptography;

class TaxBillTransformer extends TransformerAbstract {

	public function transform(TaxBill $taxBill) {
		return [
			'raw_data' => $taxBill->getRawData()
		];
	}

}
