# Debug2


[![Latest Stable Version](https://poser.pugx.org/felideo/debug2/version)](https://packagist.org/packages/felideo/debug2)
[![License](https://poser.pugx.org/felideo/debug2/license.svg)](https://packagist.org/packages/felideo/debug2)

Debug2 is a function developed to debug PHP code of form easy, simple, pretty print, fast and safe.
Its make to be a global function on entire project for fast call with a simple debug2($variable), without lost time typing a lot of keys whit unnecessaries  namespace, class and methods to instantiate a class, call a static function, or any irritant thing like that, when all you want is find  very fast the origin of bug that is exploding your ambient of  production.

The function includes a full backtrace to fast and exact location where the function is called. Because If you put a **exit** or **die** inside your code and forget remove, you never more will find!

Finaly debug2 print your code perfectily formatade and not a lot of crap like this:
```
Array ( [Animalia] => Array ( [Chordata] => Array ( [mamalia] => Array ( [0] => cat [1] => dog [2] => human ) [aves] => Array ( [0] => Ostrich [1] => chicken ) ) ) )
```


# How to use

* Install => **composer require felideo/debug2**
* Call    => **debug2($organisms, 'Organism Variable', true);**


# Example

```
<?php

$organisms = [
	'Animalia' => [
		'Chordata' => [
			'mamalia' => [
				'cat',
				'dog',
				'human'
			],
			'aves' => [
				'Ostrich',
				'chicken'
			]
		]
	]
];

debug2($organisms, 'Organism Variable', true);
```


# Result

```
============================ DEBUG2 OFICIAL ==========================
                          ORGANISM VARIABLE

Array
(
    [Animalia] => Array
        (
            [Chordata] => Array
                (
                    [mamalia] => Array
                        (
                            [0] => cat
                            [1] => dog
                            [2] => human
                        )

                    [aves] => Array
                        (
                            [0] => Ostrich
                            [1] => chicken
                        )

                )

        )

)

#0  debug2() called at [/media/armazenamento/www/FelideoMVC/modulos/index/controller/index.php:29]
#1  Controller\Index->index() called at [/media/armazenamento/www/FelideoMVC/framework/BigBang.php:102]
#2  Framework\BigBang->execute() called at [/media/armazenamento/www/FelideoMVC/framework/BigBang.php:48]
#3  Framework\BigBang->is_index() called at [/media/armazenamento/www/FelideoMVC/framework/BigBang.php:11]
#4  Framework\BigBang->expanse() called at [/media/armazenamento/www/FelideoMVC/index.php:27]

```

# P.S.
* Sorry about bad english






