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

<? if ($hasModuleName): ?>
	/**
	 * Name of the module.
	 * Set automatically by module loader.
	 */
	public $moduleName;
<? endif ?>
<? foreach ($injects as $var): ?>

	/** @Inject */
	public $<?= $var ?>;
<? endforeach ?>

<? if ($hasLogger): ?>
	/** @Logger */
	public $logger;
<? endif ?>
<? foreach ($settings as $setting): ?>

	/**
<? foreach ($setting->annos as $name => $value): ?>
	 * @<?= $name ?>("<?= $value ?>")
<? endforeach ?>
	 */
	public $default<?= $setting->name ?> = "<?= $setting->value ?>";
<? endforeach ?>

<? foreach ($vars as $var): ?>
	private $<?= $var ?>;
<? endforeach ?>

<? if ($hasSetupEvent): ?>
	/**
	 * @Setup
	 * This handler is called on bot startup.
	 */
	public function setup() {
<? foreach ($aliases as $alias): ?>
		$this->commandAlias->register($this->moduleName, '<?= $alias['command'] ?>', '<?= $alias['alias'] ?>');
<? endforeach ?>
<? foreach ($sqlFiles as $file): ?>
		$this->db->loadSQLFile($this->moduleName, '<?= $file ?>');
<? endforeach ?>
<?= $this->indent($setup, 2) ?>

	}
<? endif ?>

<? foreach ($commandHandlers as $handler): ?>
	/**
	 * This command handler <?= $handler->description ?>.
	 *
	 * @HandlesCommand("<?= $handler->command ?>")
<? foreach ($handler->matchers as $matcher): ?>
	 * @Matches("<?= $matcher ?>")
<? endforeach ?>
	 */
	public function <?= $handler->name ?>Command($message, $channel, $sender, $sendto, $args) {
	<?= $this->indent($handler->contents, 1) ?>

	}

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
