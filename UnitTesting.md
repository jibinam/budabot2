# Introduction #

In order to run the tests, you need to have following:
  * PHP 5.3 or newer,
  * PEAR 1.9.4 or newer,
  * PHPUnit unit testing framework,
  * Mockery mocking library

# Documentation #
  * [Mockery documentation](https://github.com/padraic/mockery/blob/master/README.markdown) for instruction how to write mocks and fakes.
  * [PHPUnit documentation](http://www.phpunit.de/manual/3.6/en/index.html) for instructions of how to write unit tests.
  * [Our existing unit tests](http://code.google.com/p/budabot2/source/browse/test/) for examples.

# Installing PEAR #
If you have already PEAR installed, you can update it to newer like this:
```
pear install PEAR-1.9.4
```

If you don't have PEAR yet, follow instructions in [PEAR's manual](http://pear.php.net/manual/en/installation.getting.php) to install it.

# Installing PHPUnit #
Open command prompt/bash shell/etc... and give commands:
```
pear config-set auto_discover 1
pear install pear.phpunit.de/PHPUnit
```

# Installing Mockery #
Open command prompt/bash shell/etc... and give commands:
```
pear channel-discover pear.survivethedeepend.com
pear channel-discover hamcrest.googlecode.com/svn/pear
pear install --alldeps deepend/Mockery
```

# Running unit tests #
In order to run the tests, you need to checkout Budabot's whole SVN repository, not just the trunk. After you have checkout the codes, you should have at least 'test' and 'trunk' directories in your working copy.
Now, open command prompt/bash shell/etc... and cd to your working copy.

To run all unit tests, give command:
```
phpunit test
```
If all tests passed, you should see something like this:
```
C:\Sources\Budabot>phpunit test
PHPUnit 3.6.3 by Sebastian Bergmann.

.......

Time: 0 seconds, Memory: 3.75Mb

OK (7 tests, 3 assertions)

C:\Sources\Budabot>
```

---

You can also run individual test cases like this:
```
phpunit test\core\CONFIG\CommandSearchControllerTest.php
```

---

Normally, the PHPUnit suppresses any debug output that your implementation or unit tests may print. You can show all output with --debug-switch, e.g:
```
phpunit --debug test
```
This is especially useful when you are debugging your code by printing variables with echo() or var\_dump().

---

You can enable color output with --colors switch:
```
phpunit --colors test
```
This is useful if you're running unit tests to check if your modifications to code has broken anything, you can see with one glance if the all tests are ok (green), or if at least one test has failed (red).