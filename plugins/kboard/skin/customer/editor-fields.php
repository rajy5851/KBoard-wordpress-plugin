<?php if($field['field_type'] == 'ip'):?>
	<input type="hidden" name="kboard_option_ip" value="<?php echo kboard_user_ip()?>">
<?php elseif($field['field_type'] == 'content'):?>
<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
	<div class="kboard-content">
		<?php if($board->use_editor):?>
			<?php wp_editor($content->content, 'kboard_content', array('media_buttons'=>$board->isAdmin(), 'editor_height'=>400))?>
		<?php else:?>
			<textarea name="kboard_content" id="kboard_content" class="<?php echo $required?>"<?php if($placeholder):?> placeholder="<?php echo esc_attr($placeholder)?>"<?php endif?>><?php echo $content->content?></textarea>
		<?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'nonmember'):?>
	<?php if($board->viewUsernameField()):?>
	<div class="kboard-attr-row">
		<label class="attr-name" for="kboard-input-member-display"><?php echo __('Author', 'kboard')?></label>
		<div class="attr-value"><input type="text" id="kboard-input-member-display" name="member_display" value="<?php echo $content->member_display?>" placeholder="<?php echo __('Author', 'kboard')?>"></div>
	</div>
	<div class="kboard-attr-row">
		<label class="attr-name" for="kboard-input-password"><?php echo __('Password', 'kboard')?></label>
		<div class="attr-value"><input type="password" id="kboard-input-password" name="password" value="<?php echo $content->password?>" placeholder="<?php echo __('Password', 'kboard')?>..."></div>
	</div>
	<?php else:?>
	<input style="display:none" type="text" name="fake-autofill-fields">
	<input style="display:none" type="password" name="fake-autofill-fields">
	<!-- 비밀글 비밀번호 필드 시작 -->
	<div class="kboard-attr-row secret-password-row"<?php if(!$content->secret):?> style="display:none"<?php endif?>>
		<label class="attr-name" for="kboard-input-password"><?php echo __('Password', 'kboard')?></label>
		<div class="attr-value"><input type="password" id="kboard-input-password" name="password" value="<?php echo $content->password?>" placeholder="<?php echo __('Password', 'kboard')?>...">
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?></div>
	</div>
	<!-- 비밀글 비밀번호 필드 끝 -->
	<?php endif?>
<?php elseif($field['field_type'] == 'captcha'):?>
	<?php if($board->useCAPTCHA() && !$content->uid):?>
		<?php if(kboard_use_recaptcha()):?>
		<div class="kboard-attr-row">
			<label class="attr-name"></label>
			<div class="attr-value"><div class="g-recaptcha" data-sitekey="<?php echo kboard_recaptcha_site_key()?>"></div>
			<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?></div>
		</div>
		<?php else:?>
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard-input-captcha"><img src="<?php echo kboard_captcha()?>" alt=""></label>
			<div class="attr-value"><input type="text" id="kboard-input-captcha" name="captcha" value="" placeholder="<?php echo __('CAPTCHA', 'kboard')?>...">
			<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?></div>
		</div>
		<?php endif?>
	<?php endif?>
<?php elseif($field['field_type'] == 'attach'):?>
	<?php if($board->meta->max_attached_count > 0):?>
		<!-- 첨부파일 시작 -->
		<?php for($attached_index=1; $attached_index<=$board->meta->max_attached_count; $attached_index++):?>
		<div class="kboard-attr-row">
			<label class="attr-name" for="kboard-input-file<?php echo $attached_index?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php echo $attached_index?></label>
			<div class="attr-value">
				<?php if(isset($content->attach->{"file{$attached_index}"})):?><?php echo $content->attach->{"file{$attached_index}"}[1]?> - <a href="<?php echo $url->getDeleteURLWithAttach($content->uid, "file{$attached_index}")?>" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete file', 'kboard')?></a><?php endif?>
				<input type="file" id="kboard-input-file<?php echo $attached_index?>" name="kboard_attach_file<?php echo $attached_index?>">
				<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
			</div>
		</div>
		<?php endfor?>
		<!-- 첨부파일 끝 -->
	<?php endif?>
<?php elseif($field['field_type'] == 'category1'):?>
	<?php if(!$board->isTreeCategoryActive()):?>
		<?php if($board->initCategory1()):?>
			<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
				<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
				<div class="attr-value">
					<select id="<?php echo esc_attr($meta_key)?>" name="category1" class="<?php echo $required?>">
						<option value=""><?php echo __('Category', 'kboard')?> <?php echo __('Select', 'kboard')?></option>
						<?php while($board->hasNextCategory()):?>
						<option value="<?php echo $board->currentCategory()?>"<?php if($content->category1 == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
						<?php endwhile?>
					</select>
					<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
				</div>
			</div>
		<?php endif?>
	<?php endif?>
<?php elseif($field['field_type'] == 'category2'):?>
	<?php if(!$board->isTreeCategoryActive()):?>
		<?php if($board->initCategory2()):?>
			<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
				<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
				<div class="attr-value">
					<select id="<?php echo esc_attr($meta_key)?>" name="category2" class="<?php echo $required?>">
						<option value=""><?php echo __('Category', 'kboard')?> <?php echo __('Select', 'kboard')?></option>
						<?php while($board->hasNextCategory()):?>
						<option value="<?php echo $board->currentCategory()?>"<?php if($content->category2 == $board->currentCategory()):?> selected<?php endif?>><?php echo $board->currentCategory()?></option>
						<?php endwhile?>
					</select>
					<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
				</div>
			</div>
		<?php endif?>
	<?php endif?>
<?php elseif($field['field_type'] == 'tree_category'):?>
	<?php if($board->isTreeCategoryActive()):?>
		<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
			<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span></label>
			<div class="attr-value">
				<?php for($i=1; $i<=$content->getTreeCategoryDepth(); $i++):?>
				<input type="hidden" id="tree-category-check-<?php echo $i?>" value="<?php echo $content->option->{'tree_category_'.$i}?>">
				<input type="hidden" name="kboard_option_tree_category_<?php echo $i?>" value="">
				<?php endfor?>
				<div class="kboard-tree-category-wrap"></div>
				<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
			</div>
		</div>
	<?php endif?>
<?php elseif($field['field_type'] == 'title'):?>
<div class="kboard-attr-row <?php echo $field['class']?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span> <span class="attr-required-text">*</span></label>
	<div class="attr-value">
		<input type="text" id="<?php echo esc_attr($meta_key)?>" name="title" value="<?php echo $content->title?$content->title:esc_attr($default_value)?>"<?php if($placeholder):?> placeholder="<?php echo esc_attr($placeholder)?>"<?php endif?>>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'option'):?>
<div class="kboard-attr-row <?php echo $field['class']?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span></label>
	<div class="attr-value">
		<label class="attr-value-option"><input type="checkbox" name="secret" value="true" onchange="kboard_toggle_password_field(this)"<?php if($content->secret):?> checked<?php endif?>> <?php echo __('Secret', 'kboard')?></label>
		<?php if($board->isAdmin()):?>
		<label class="attr-value-option"><input type="checkbox" name="notice" value="true"<?php if($content->notice):?> checked<?php endif?>> <?php echo __('Notice', 'kboard')?></label>
		<?php endif?>
		<?php do_action('kboard_skin_editor_option', $content, $board, $board->boardBuilder)?>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'media'):?>
<div class="kboard-attr-row <?php echo $field['class']?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span></label>
	<div class="attr-value">
		<a href="#" onclick="kboard_editor_open_media();return false;"><?php echo __('KBoard Add Media', 'kboard')?></a>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'thumbnail'):?>
<div class="kboard-attr-row <?php echo $field['class']?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span></label>
	<div class="attr-value">
		<?php if($content->thumbnail_file):?><?php echo $content->thumbnail_name?> - <a href="<?php echo $url->getDeleteURLWithAttach($content->uid);?>" onclick="return confirm('<?php echo __('Are you sure you want to delete?', 'kboard')?>');"><?php echo __('Delete file', 'kboard')?></a><?php endif?>
		<input type="file" id="kboard-input-thumbnail" name="thumbnail" accept="image/*">
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'search'):?>
	<?php if(isset($field['hidden']) && $field['hidden'] == '1'):?>
		<input type="hidden" name="wordpress_search" value="<?php echo esc_attr($default_value)?>">
	<?php else:?>
		<div class="kboard-attr-row <?php echo $field['class']?>">
			<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span></label>
			<div class="attr-value">
				<select id="kboard-select-wordpress-search" name="wordpress_search">
					<option value="1"<?php if($wordpress_search == '1'):?> selected<?php endif?>><?php echo __('Public', 'kboard')?></option>
					<option value="2"<?php if($wordpress_search == '2'):?> selected<?php endif?>><?php echo __('Only title (secret document)', 'kboard')?></option>
					<option value="3"<?php if($wordpress_search == '3'):?> selected<?php endif?>><?php echo __('Exclusion', 'kboard')?></option>
				</select>
				<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
			</div>
		</div>
	<?php endif?>
<?php elseif($field['field_type'] == 'text'):?>
<?php if(isset($field['hidden']) && $field['hidden']):?>
	<input type="hidden" id="<?php echo esc_attr($meta_key)?>" class="<?php echo $required?>" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>" value="<?php echo $content->option->{esc_attr($meta_key)}?$content->option->{esc_attr($meta_key)}:esc_attr($default_value)?>">
<?php else:?>
<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
	<div class="attr-value">
		<input type="text" id="<?php echo esc_attr($meta_key)?>" class="<?php echo $required?>" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>" value="<?php echo $content->option->{esc_attr($meta_key)}?$content->option->{esc_attr($meta_key)}:esc_attr($default_value)?>"<?php if($placeholder):?> placeholder="<?php echo esc_attr($placeholder)?>"<?php endif?>>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php endif?>
<?php elseif($field['field_type'] == 'select'):?>
<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
	<div class="attr-value">
		<select id="<?php echo esc_attr($meta_key)?>" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>"class="<?php echo $required?>">
			<option value="default">선택</option>
			<?php foreach($field['row'] as $option_key=>$option_value):?>
				<?php if(isset($option_value['label']) && $option_value['label']):?>
					<?php if($content->option->{$meta_key}):?>
						<option value="<?php echo esc_attr($option_value['label'])?>"<?php if($fields->isSavedOption($content->option->{$meta_key}, $option_value['label'])):?> selected<?php endif?>><?php echo esc_attr($option_value['label'])?></option>
					<?php else:?>
						<option value="<?php echo esc_attr($option_value['label'])?>"<?php if($fields->isSavedOption($default_value, $option_key)):?> selected<?php endif?>><?php echo esc_attr($option_value['label'])?></option>
					<?php endif?>
				<?php endif?>
			<?php endforeach?>
		</select>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'radio'):?>
	<?php if(isset($field['row']) && $field['row']):?>
	<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
		<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
		<div class="attr-value">
		<?php foreach($field['row'] as $option_key=>$option_value):?>
			<?php if(isset($option_value['label']) && $option_value['label']):?>
				<?php if($content->option->{$meta_key}):?>
					<label><input type="radio" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>"class="<?php echo $required?>"<?php if($fields->isSavedOption($content->option->{$meta_key}, $option_value['label'])):?> checked<?php endif?> value="<?php echo esc_attr($option_value['label'])?>"><?php echo esc_attr($option_value['label'])?></label>
				<?php else:?>
					<label><input type="radio" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>"class="<?php echo $required?>"<?php if($fields->isSavedOption($default_value, $option_key)):?> checked<?php endif?> value="<?php echo esc_attr($option_value['label'])?>"><?php echo esc_attr($option_value['label'])?></label>
				<?php endif?>
			<?php endif?>
		<?php endforeach?>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
		</div>
	</div>
	<?php endif?>
<?php elseif($field['field_type'] == 'checkbox'):?>
	<?php if(isset($field['row']) && $field['row']):?>
	<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
		<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
		<div class="attr-value">
			<?php foreach($field['row'] as $option_key=>$option_value):?>
				<?php if(isset($option_value['label']) && $option_value['label']):?>
					<?php $default_value = (isset($option_value['default_value']) && $option_value['default_value']) ? $option_value['default_value'] : ''?>
					<?php if($content->option->{$meta_key}):?>
						<label><input type="checkbox" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>[]"class="<?php echo $required?>"<?php if($fields->isSavedOption($content->option->{$meta_key}, $option_value['label'])):?> checked<?php endif?> value="<?php echo esc_attr($option_value['label'])?>"><?php echo esc_attr($option_value['label'])?></label>
					<?php else:?>
						<label><input type="checkbox" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>[]"class="<?php echo $required?>"<?php if($fields->isSavedOption($default_value, $option_value['label'])):?> checked<?php endif?> value="<?php echo esc_attr($option_value['label'])?>"><?php echo esc_attr($option_value['label'])?></label>
					<?php endif?>
				<?php endif?>
			<?php endforeach?>
			<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
		</div>
	</div>
	<?php endif?>
<?php elseif($field['field_type'] == 'textarea'):?>
<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
	<div class="attr-value">
		<textarea id="<?php echo esc_attr($meta_key)?>" name="<?php echo $fields->getOptionFieldName(esc_attr($meta_key))?>"class="<?php echo $required?>"<?php if($placeholder):?> placeholder="<?php echo esc_attr($placeholder)?>"<?php endif?>><?php echo $content->option->{esc_attr($meta_key)}?$content->option->{esc_attr($meta_key)}:esc_attr($default_value)?></textarea>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php elseif($field['field_type'] == 'wp_editor'):?>
<div class="kboard-attr-row <?php echo $field['class']?> <?php echo $required?>">
	<label class="attr-name" for="<?php echo esc_attr($meta_key)?>"><span class="field-name"><?php echo esc_html($field_name)?></span><?php if($required):?> <span class="attr-required-text">*</span><?php endif?></label>
	<div class="attr-value">
		<?php wp_editor($content->option->{esc_attr($meta_key)}?$content->option->{esc_attr($meta_key)}:esc_attr($default_value), $fields->getOptionFieldName(esc_attr($meta_key)), array('media_buttons'=>$board->isAdmin(), 'editor_height'=>400, 'editor_class'=>$required))?>
		<?php if(isset($field['description']) && $field['description']):?><div class="description"><?php echo esc_html($field['description'])?></div><?php endif?>
	</div>
</div>
<?php endif?>