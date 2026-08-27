# Debug2


[![Latest Stable Version](https://poser.pugx.org/felideo/debug2/version)](https://packagist.org/packages/felideo/debug2)
[![License](https://poser.pugx.org/felideo/debug2/license.svg)](https://packagist.org/packages/felideo/debug2)


Debug2 is a package developed to debug PHP code of form easy, simple, pretty print, fast, and safe.
It's made to be a global function on the entire project for fast calls with a simple **function($variable)**, without lost time typing a lot of keys with unnecessary namespace, class, and methods to instantiate a class, call a static function, or any irritant thing like that, when all you want is see or find something very fast.

The functions include a backtrace to the fast and exact location where the function is called. Because If you put an **exit** or **die** inside your code and forget to remove it, you never more will find!

Finally debug2 print your code perfectly formatted and not a lot of crap, hard to read and understand like this:
```
Array ( [Animalia] => Array ( [Chordata] => Array ( [mamalia] => Array ( [0] => cat [1] => dog [2] => human ) [aves] => Array ( [0] => Ostrich [1] => chicken ) ) ) )
```

# How to use

* **Install** => composer require felideo/debug2
* **Functions** => debug1, debug2, reflect

# Description

### Debug1 and debug2

The unique difference between debug1 and debug2 it's that on debug1 the backtrace is limited to 3 entries and debug2 shows a full backtrace.

```
debug1(
	mixed  $debug,
	?string  $title,
	?string  $exit
): void

debug2(
	mixed  $debug,
	?string  $title,
	?string  $exit
): void
```

#### Example

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


#### Result

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

### Reflect

Reflect is an implementation of the [Reflection](https://www.php.net/manual/en/book.reflection.php) PHP class to see all possible information about a class at once.

```
reflect(
	object  $instance_of_a_class,
	?string  $method,
	?string  $exit
): void
```

#### Example

```
<?php
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

Collection::macro('toUpper', function  () {
		return  $this->map(function  (string  $value) {
		return  Str::upper($value);
	});
});

$collection  =  collect(['first',  'second']);

reflect($collection, 'count', true);
```


#### Result

```
============================ REFLECT OFICIAL ==========================
               ILLUMINATE\DATABASE\ELOQUENT\COLLECTION

Array
(
    [class      ] => Collection
    [namespace  ] => Illuminate\Database\Eloquent
    [full_name  ] => Illuminate\Database\Eloquent\Collection
    [file       ] => engine-backend/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Collection.php
    [constructor] => __construct => Illuminate\Support\Collection => engine-backend/vendor/laravel/framework/src/Illuminate/Collections/Collection.php
    [size       ] => 13 => 750
    [method] => Array
        (
            [method     ] => count
            [parameters ] => Array
                (
                )

            [doc_comment] => /**
     * Count the number of items in the collection.
     *
     * @return int
     */
            [toString   ] =>
/**
     * Count the number of items in the collection.
     *
     * @return int
     */
Method [ <user, inherits Illuminate\Support\Collection, prototype Countable> public method count ] {
  @@ /home/vagrant/code/engine-backend/vendor/laravel/framework/src/Illuminate/Collections/Collection.php 1581 - 1584
}

        )

    [parents] => Array
        (
            [0] => Illuminate\Support\Collection
        )

    [traits] => Array
        (
        )

    [properties] => Array
        (
            [0] => escapeWhenCastingToString => Illuminate\Support\Collection
            [1] => items => Illuminate\Support\Collection
            [2] => macros => Illuminate\Support\Collection
            [3] => proxies => Illuminate\Support\Collection
        )

    [methods] => Array
        (
            [0] => __call => Illuminate\Support\Collection
            [1] => __callStatic => Illuminate\Support\Collection
            [2] => __construct => Illuminate\Support\Collection
            [3] => __get => Illuminate\Support\Collection
            [4] => __toString => Illuminate\Support\Collection
            [5] => add => Illuminate\Support\Collection
            [6] => all => Illuminate\Support\Collection
            [7] => append => Illuminate\Database\Eloquent\Collection
            [8] => average => Illuminate\Support\Collection
            [9] => avg => Illuminate\Support\Collection
            [10] => chunk => Illuminate\Support\Collection
            [11] => chunkWhile => Illuminate\Support\Collection
            [12] => collapse => Illuminate\Database\Eloquent\Collection
            [13] => collect => Illuminate\Support\Collection
            [14] => combine => Illuminate\Support\Collection
            [15] => concat => Illuminate\Support\Collection
            [16] => contains => Illuminate\Database\Eloquent\Collection
            [17] => containsOneItem => Illuminate\Support\Collection
            [18] => containsStrict => Illuminate\Support\Collection
            [19] => count => Illuminate\Support\Collection
            [20] => countBy => Illuminate\Support\Collection
            [21] => crossJoin => Illuminate\Support\Collection
            [22] => dd => Illuminate\Support\Collection
            [23] => diff => Illuminate\Database\Eloquent\Collection
            [24] => diffAssoc => Illuminate\Support\Collection
            [25] => diffAssocUsing => Illuminate\Support\Collection
            [26] => diffKeys => Illuminate\Support\Collection
            [27] => diffKeysUsing => Illuminate\Support\Collection
            [28] => diffUsing => Illuminate\Support\Collection
            [29] => doesntContain => Illuminate\Support\Collection
            [30] => dump => Illuminate\Support\Collection
            [31] => duplicateComparator => Illuminate\Database\Eloquent\Collection
            [32] => duplicates => Illuminate\Support\Collection
            [33] => duplicatesStrict => Illuminate\Support\Collection
            [34] => each => Illuminate\Support\Collection
            [35] => eachSpread => Illuminate\Support\Collection
            [36] => empty => Illuminate\Support\Collection
            [37] => equality => Illuminate\Support\Collection
            [38] => escapeWhenCastingToString => Illuminate\Support\Collection
            [39] => every => Illuminate\Support\Collection
            [40] => except => Illuminate\Database\Eloquent\Collection
            [41] => filter => Illuminate\Support\Collection
            [42] => find => Illuminate\Database\Eloquent\Collection
            [43] => first => Illuminate\Support\Collection
            [44] => firstOrFail => Illuminate\Support\Collection
            [45] => firstWhere => Illuminate\Support\Collection
            [46] => flatMap => Illuminate\Support\Collection
            [47] => flatten => Illuminate\Database\Eloquent\Collection
            [48] => flip => Illuminate\Database\Eloquent\Collection
            [49] => flushMacros => Illuminate\Support\Collection
            [50] => forPage => Illuminate\Support\Collection
            [51] => forget => Illuminate\Support\Collection
            [52] => fresh => Illuminate\Database\Eloquent\Collection
            [53] => get => Illuminate\Support\Collection
            [54] => getArrayableItems => Illuminate\Support\Collection
            [55] => getCachingIterator => Illuminate\Support\Collection
            [56] => getDictionary => Illuminate\Database\Eloquent\Collection
            [57] => getIterator => Illuminate\Support\Collection
            [58] => getOrPut => Illuminate\Support\Collection
            [59] => getQueueableClass => Illuminate\Database\Eloquent\Collection
            [60] => getQueueableConnection => Illuminate\Database\Eloquent\Collection
            [61] => getQueueableIds => Illuminate\Database\Eloquent\Collection
            [62] => getQueueableRelations => Illuminate\Database\Eloquent\Collection
            [63] => groupBy => Illuminate\Support\Collection
            [64] => has => Illuminate\Support\Collection
            [65] => hasAny => Illuminate\Support\Collection
            [66] => hasMacro => Illuminate\Support\Collection
            [67] => identity => Illuminate\Support\Collection
            [68] => implode => Illuminate\Support\Collection
            [69] => intersect => Illuminate\Database\Eloquent\Collection
            [70] => intersectByKeys => Illuminate\Support\Collection
            [71] => isEmpty => Illuminate\Support\Collection
            [72] => isNotEmpty => Illuminate\Support\Collection
            [73] => join => Illuminate\Support\Collection
            [74] => jsonSerialize => Illuminate\Support\Collection
            [75] => keyBy => Illuminate\Support\Collection
            [76] => keys => Illuminate\Database\Eloquent\Collection
            [77] => last => Illuminate\Support\Collection
            [78] => lazy => Illuminate\Support\Collection
            [79] => load => Illuminate\Database\Eloquent\Collection
            [80] => loadAggregate => Illuminate\Database\Eloquent\Collection
            [81] => loadAvg => Illuminate\Database\Eloquent\Collection
            [82] => loadCount => Illuminate\Database\Eloquent\Collection
            [83] => loadExists => Illuminate\Database\Eloquent\Collection
            [84] => loadMax => Illuminate\Database\Eloquent\Collection
            [85] => loadMin => Illuminate\Database\Eloquent\Collection
            [86] => loadMissing => Illuminate\Database\Eloquent\Collection
            [87] => loadMissingRelation => Illuminate\Database\Eloquent\Collection
            [88] => loadMorph => Illuminate\Database\Eloquent\Collection
            [89] => loadMorphCount => Illuminate\Database\Eloquent\Collection
            [90] => loadSum => Illuminate\Database\Eloquent\Collection
            [91] => macro => Illuminate\Support\Collection
            [92] => make => Illuminate\Support\Collection
            [93] => makeHidden => Illuminate\Database\Eloquent\Collection
            [94] => makeVisible => Illuminate\Database\Eloquent\Collection
            [95] => map => Illuminate\Database\Eloquent\Collection
            [96] => mapInto => Illuminate\Support\Collection
            [97] => mapSpread => Illuminate\Support\Collection
            [98] => mapToDictionary => Illuminate\Support\Collection
            [99] => mapToGroups => Illuminate\Support\Collection
            [100] => mapWithKeys => Illuminate\Database\Eloquent\Collection
            [101] => max => Illuminate\Support\Collection
            [102] => median => Illuminate\Support\Collection
            [103] => merge => Illuminate\Database\Eloquent\Collection
            [104] => mergeRecursive => Illuminate\Support\Collection
            [105] => min => Illuminate\Support\Collection
            [106] => mixin => Illuminate\Support\Collection
            [107] => mode => Illuminate\Support\Collection
            [108] => modelKeys => Illuminate\Database\Eloquent\Collection
            [109] => negate => Illuminate\Support\Collection
            [110] => nth => Illuminate\Support\Collection
            [111] => offsetExists => Illuminate\Support\Collection
            [112] => offsetGet => Illuminate\Support\Collection
            [113] => offsetSet => Illuminate\Support\Collection
            [114] => offsetUnset => Illuminate\Support\Collection
            [115] => only => Illuminate\Database\Eloquent\Collection
            [116] => operatorForWhere => Illuminate\Support\Collection
            [117] => pad => Illuminate\Database\Eloquent\Collection
            [118] => partition => Illuminate\Support\Collection
            [119] => pipe => Illuminate\Support\Collection
            [120] => pipeInto => Illuminate\Support\Collection
            [121] => pipeThrough => Illuminate\Support\Collection
            [122] => pluck => Illuminate\Database\Eloquent\Collection
            [123] => pop => Illuminate\Support\Collection
            [124] => prepend => Illuminate\Support\Collection
            [125] => proxy => Illuminate\Support\Collection
            [126] => pull => Illuminate\Support\Collection
            [127] => push => Illuminate\Support\Collection
            [128] => put => Illuminate\Support\Collection
            [129] => random => Illuminate\Support\Collection
            [130] => range => Illuminate\Support\Collection
            [131] => reduce => Illuminate\Support\Collection
            [132] => reduceMany => Illuminate\Support\Collection
            [133] => reduceSpread => Illuminate\Support\Collection
            [134] => reduceWithKeys => Illuminate\Support\Collection
            [135] => reject => Illuminate\Support\Collection
            [136] => replace => Illuminate\Support\Collection
            [137] => replaceRecursive => Illuminate\Support\Collection
            [138] => reverse => Illuminate\Support\Collection
            [139] => search => Illuminate\Support\Collection
            [140] => shift => Illuminate\Support\Collection
            [141] => shuffle => Illuminate\Support\Collection
            [142] => skip => Illuminate\Support\Collection
            [143] => skipUntil => Illuminate\Support\Collection
            [144] => skipWhile => Illuminate\Support\Collection
            [145] => slice => Illuminate\Support\Collection
            [146] => sliding => Illuminate\Support\Collection
            [147] => sole => Illuminate\Support\Collection
            [148] => some => Illuminate\Support\Collection
            [149] => sort => Illuminate\Support\Collection
            [150] => sortBy => Illuminate\Support\Collection
            [151] => sortByDesc => Illuminate\Support\Collection
            [152] => sortByMany => Illuminate\Support\Collection
            [153] => sortDesc => Illuminate\Support\Collection
            [154] => sortKeys => Illuminate\Support\Collection
            [155] => sortKeysDesc => Illuminate\Support\Collection
            [156] => sortKeysUsing => Illuminate\Support\Collection
            [157] => splice => Illuminate\Support\Collection
            [158] => split => Illuminate\Support\Collection
            [159] => splitIn => Illuminate\Support\Collection
            [160] => sum => Illuminate\Support\Collection
            [161] => take => Illuminate\Support\Collection
            [162] => takeUntil => Illuminate\Support\Collection
            [163] => takeWhile => Illuminate\Support\Collection
            [164] => tap => Illuminate\Support\Collection
            [165] => times => Illuminate\Support\Collection
            [166] => toArray => Illuminate\Support\Collection
            [167] => toBase => Illuminate\Support\Collection
            [168] => toJson => Illuminate\Support\Collection
            [169] => toQuery => Illuminate\Database\Eloquent\Collection
            [170] => transform => Illuminate\Support\Collection
            [171] => undot => Illuminate\Support\Collection
            [172] => union => Illuminate\Support\Collection
            [173] => unique => Illuminate\Database\Eloquent\Collection
            [174] => uniqueStrict => Illuminate\Support\Collection
            [175] => unless => Illuminate\Support\Collection
            [176] => unlessEmpty => Illuminate\Support\Collection
            [177] => unlessNotEmpty => Illuminate\Support\Collection
            [178] => unwrap => Illuminate\Support\Collection
            [179] => useAsCallable => Illuminate\Support\Collection
            [180] => valueRetriever => Illuminate\Support\Collection
            [181] => values => Illuminate\Support\Collection
            [182] => when => Illuminate\Support\Collection
            [183] => whenEmpty => Illuminate\Support\Collection
            [184] => whenNotEmpty => Illuminate\Support\Collection
            [185] => where => Illuminate\Support\Collection
            [186] => whereBetween => Illuminate\Support\Collection
            [187] => whereIn => Illuminate\Support\Collection
            [188] => whereInStrict => Illuminate\Support\Collection
            [189] => whereInstanceOf => Illuminate\Support\Collection
            [190] => whereNotBetween => Illuminate\Support\Collection
            [191] => whereNotIn => Illuminate\Support\Collection
            [192] => whereNotInStrict => Illuminate\Support\Collection
            [193] => whereNotNull => Illuminate\Support\Collection
            [194] => whereNull => Illuminate\Support\Collection
            [195] => whereStrict => Illuminate\Support\Collection
            [196] => wrap => Illuminate\Support\Collection
            [197] => zip => Illuminate\Database\Eloquent\Collection
        )

)

#0 /home/vagrant/code/engine-backend/vendor/felideo/debug2/src/debug2.php(98): debug2_footer()
#1 /home/vagrant/code/engine-backend/app/Models/Virtual/Item.php(411): reflect()
#2 /home/vagrant/code/engine-backend/api/VirtualEntityRecord/Controllers/Base.php(492): App\Models\Virtual\Item->virtualEntityIdentificationByAliasId()
```
