<?= '<?php' ?>

/**
 * @Instance
 *
<? if (count($defines)): ?>
 * Commands this controller contains:
<? foreach ($defines as $define): ?>
 *	@DefineCommand(
<? foreach ($define as $arg): ?>
 *		<?= str_pad($arg[0], $defineKeySpace) ?> = '<?= $arg[1] ?>'<? if (!$arg[2]): ?>,<? endif ?>

<? endforeach ?>
 *	)
<? endforeach ?>
<? endif ?>
 */
class <?= $moduleName ?>Controller {

<? foreach ($injects as $var): ?>
	/** @Inject */
	public $<?= $var ?>;
<? endforeach ?>

<? foreach ($vars as $var): ?>
	private $<?= $var ?>;
<? endforeach ?>

<? foreach ($events as $event): ?>
	/**
	 * This event handler <?= $event->description ?>.
	 *
<? foreach ($event->annos as $name => $value): ?>
	 * @<?= $name ?>("<?= $value ?>")
<? endforeach ?>
	 */
	public function <?= lcfirst($event->name) ?>Event($eventObj) {
<?= $this->indent($event->contents, 2) ?>
	}

<? endforeach ?>
}
