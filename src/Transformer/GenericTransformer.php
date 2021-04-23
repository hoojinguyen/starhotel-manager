<?php

namespace Romi\Transformer;

use League\Fractal\TransformerAbstract;
use Romi\Shared\ResultObject;

class GenericTransformer extends TransformerAbstract {

	public function transform(ResultObject $object) {
		$fieldname = $object->getName();
		
		return [
			'result' => $object->getResult(),
			$fieldname => $object->getData()
		];
	}

}
