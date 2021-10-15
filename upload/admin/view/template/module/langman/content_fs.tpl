<div id="langman-filter-top" class="langman-filter-fs">
	<?php include('filter_fs.tpl') ?>
</div>

<!--Один файл-->
<?php if ($post['v'] == 1) { ?>
<h2><strong><?= $data->dir_name ?></strong></h2>
<table class="langman-data">
	<caption>
		<h3><?= $data->file_name . '.php' ?></h3>
	</caption>
	<thead>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach($data->content as $group => $variables){ ?>
		<tr class="tr-groups">
			<td><input type="checkbox" title="" /></td>
			<td><b><?= $group ?></b></td>
			<td colspan="<?= count($languages) ?>"></td>
		</tr>
		<?php foreach($variables as $variable => $langs) { ?>
		<tr class="tr-vars">
			<td class="td-checkbox">
				<input type="checkbox" value="<?= $post['d'] . '.' . $post['f'] ?>" title="" />
			</td>
			<td class="td-var">
				<p id="var.<?= $post['d'] . '.' . $post['f'] ?>"><?= $variable ?></p>
			</td>
			<?php foreach($languages as $language) { ?>
			<td class="td-translate">
				<?= $langs->$language['directory'] ?>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<h2><strong><?= $data->dir_name ?></strong></h2>

<!--Все файлы папки-->
<?php } elseif ($post['v'] == 2) { ?>
<h2><strong><?= $data->dir_name ?></strong></h2>
<?php foreach(($files = $data->content) as $num => $file) { ?>
<table class="langman-data">
	<caption>
		<h3><?= $file->file_name . '.php' ?></h3>
	</caption>
	<thead>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach($file as $group => $variables){ ?>
		<?php if (is_object($variables)) { ?>
		<tr class="tr-groups">
			<td><input type="checkbox" title="" /></td>
			<td><b><?= $group ?></b></td>
			<td colspan="<?= count($languages) ?>"></td>
		</tr>
		<?php foreach($variables as $variable => $langs) { ?>
		<tr class="tr-vars">
			<td class="td-checkbox">
				<input type="checkbox" value="<?= $post['d'] . '.' . $post['f'] ?>" title="" />
			</td>
			<td class="td-var">
				<p id="var.<?= $post['d'] . '.' . $post['f'] ?>"><?= $variable ?></p>
			</td>
			<?php foreach($languages as $language) { ?>
			<td class="td-translate">
				<?= $langs->$language['directory'] ?>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<?php } ?>
<h2><strong><?= $data->dir_name ?></strong></h2>

<!--Все файлы-->
<?php } elseif ($post['v'] == 3) { ?>
<?php foreach($data as $num => $directory) { ?>
<h2><strong><?= $directory[0] ?></strong></h2>
<?php foreach($directory as $num2 => $file) { ?>
<?php if (is_object($file)) { ?>
<table class="langman-data">
	<caption>
		<h3><?= $file->file_name . '.php' ?></h3>
	</caption>
	<thead>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><input type="checkbox" title="" /></th>
			<th></th>
			<?php foreach($languages as $language) { ?>
			<th><?= $language['name'] . ' (' . $language['directory'] . ')' ?></th>
			<?php } ?>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach($file as $group => $variables){ ?>
		<?php if (is_object($variables)) { ?>
		<tr class="tr-groups">
			<td><input type="checkbox" title="" /></td>
			<td><b><?= $group ?></b></td>
			<td colspan="<?= count($languages) ?>"></td>
		</tr>
		<?php foreach($variables as $variable => $langs) { ?>
		<tr class="tr-vars">
			<td class="td-checkbox">
				<input type="checkbox" value="<?= $post['d'] . '.' . $post['f'] ?>" title="" />
			</td>
			<td class="td-var">
				<p id="var.<?= $post['d'] . '.' . $post['f'] ?>"><?= $variable ?></p>
			</td>
			<?php foreach($languages as $language) { ?>
			<td class="td-translate">
				<?= $langs->$language['directory'] ?>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<?php } ?>
<?php } ?>
<?php } ?>
<?php } ?>


<div id="langman-filter-bottom" class="langman-filter-fs">
	<?php include('filter_fs.tpl') ?>
</div>