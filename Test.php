<?php

include 'Singleton.php';

function assertResult($result) {
	if ($result) {
		return true;
	} else {
		return false;
	}
}	

// Kad kiekvieno testo metu butu kuriamas naujas Singleton'as
function resetSingleton() {
	$singleton = Singleton::getInstance();
	$refSingleton = new ReflectionObject($singleton);
	$instanceProperty = $refSingleton->getProperty('instance');
	$instanceProperty->setAccessible(true);
	$instanceProperty->setValue(null, null);
	$instanceProperty->setAccessible(false);
	unset($singleton);
}

$tests = [
	'singletonReturnsInstanceOfSingleton' => function() {
		$singleton = Singleton::getInstance();
		return assertResult($singleton instanceof Singleton);
	},
	'singletonReturnsReferenceToSameObject' => function() {
		$firstReference = Singleton::getInstance();
		$secondReference = Singleton::getInstance();
		return assertResult($firstReference === $secondReference);
	},
	'singletonReturnsNewInstanceAfterSettingReferenceToNull' => function() {
		$firstReference = Singleton::getInstance();
		resetSingleton();
		$secondReference = Singleton::getInstance();
		return assertResult($firstReference !== $secondReference);
	},
];

foreach ($tests as $name => $function) {
	resetSingleton();
	if (call_user_func($function)) {
		echo "$name passed \n";
	} else {
		echo "~~$name failed ~~\n";
	}
}
