<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if (isset($error_warning)) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"> <?php echo $content_top; ?>
      <h2><?php echo $text_edit_extension; ?></h2>
      <?php if (!$approved) { ?><div class="bg-warning text-warning"><?php $warning_not_approved; ?></div><?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_general; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_general; ?></div>
          <div class="form-group col-sm-12">
            <label for="input-name"><?php echo $entry_name; ?></label>
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if (!empty($error_name)) { ?><div class="text-danger"><?php echo $error_name; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-category-id"><?php echo $entry_category; ?></label>
            <select name="category_id" id="input-category-id" class="form-control">
            <?php foreach ($categories as $category) { ?>
              <?php if ($category_id == $category['category_id']) { ?>
              <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
              <?php } else { ?>  
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
              <?php } ?>
            <?php } ?>
            </select>
            <?php if (!empty($error_category_id)) { ?><div class="text-danger"><?php echo $error_category_id; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-description"><?php echo $entry_description; ?></label>
            <textarea name="description" rows="12" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo $description; ?></textarea>
            <?php if (!empty($error_description)) { ?><div class="text-danger"><?php echo $error_description; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-documentation"><?php echo $entry_documentation; ?></label>
            <textarea name="documentation" rows="12" placeholder="<?php echo $entry_documentation; ?>" id="input-documentation" class="form-control"><?php echo $documentation; ?></textarea>
            <?php if (!empty($error_documentation)) { ?><div class="text-danger"><?php echo $error_documentation; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-changelog"><?php echo $entry_changelog; ?></label>
            <textarea name="changelog" rows="12" placeholder="<?php echo $entry_changelog; ?>" id="input-changelog" class="form-control"><?php echo $changelog; ?></textarea>
            <?php if (!empty($error_changelog)) { ?><div class="text-danger"><?php echo $error_changelog; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-tag"><?php echo $entry_tag; ?></label>
            <textarea name="tag" rows="6" placeholder="<?php echo $entry_tag; ?>" id="input-tag" class="form-control"><?php echo $tag; ?></textarea>
            <?php if (!empty($error_tag)) { ?><div class="text-danger"><?php echo $error_tag; ?></div><?php } ?>
          </div>
        </fieldset>
        <fieldset>
          <legend><?php echo $text_price; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_price; ?></div>
          <div class="form-group col-sm-12">
            <div class="col-sm-8">
              <label for="input-license"><?php echo $entry_license; ?></label>
              <select name="license" id="input-license" class="form-control">
              <?php foreach ($license_types as $value) { ?>
                <?php if ($license == $value) { ?>
                <option value="<?php echo $value; ?>" selected="selected"><?php echo ${'text_license_type_' . $value}; ?></option>
                <?php } else { ?>  
                <option value="<?php echo $value; ?>"><?php echo ${'text_license_type_' . $value}; ?></option>
                <?php } ?>
              <?php } ?>
              </select>
              <?php if (!empty($error_license)) { ?><div class="text-danger"><?php echo $error_license; ?></div><?php } ?>
            </div>
            <div class="col-sm-4">
              <label for="input-price"><?php echo $entry_price; ?></label>
              <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              <?php if (!empty($error_price)) { ?><div class="text-danger"><?php echo $error_price; ?></div><?php } ?>
            </div>
          </div>
          <div class="form-group col-sm-12">
            <div class="col-sm-8">
              <label for="input-license-period"><?php echo $entry_license_period; ?></label>
              <select name="license_period" id="input-license-period" class="form-control">
              <?php foreach ($license_periods as $value) { ?>
                <?php if ($license_period == $value) { ?>
                <option value="<?php echo $value; ?>" selected="selected"><?php echo ${'text_license_period_' . $value}; ?></option>
                <?php } else { ?>  
                <option value="<?php echo $value; ?>"><?php echo ${'text_license_period_' . $value}; ?></option>
                <?php } ?>
              <?php } ?>
              </select>
              <?php if (!empty($error_license_period)) { ?><div class="text-danger"><?php echo $error_license_period; ?></div><?php } ?>
            </div>
            <div class="col-sm-4">
              <label for="input-price-renew"><?php echo $entry_price_renew; ?></label>
              <input type="text" name="price_renew" value="<?php echo $price_renew; ?>" placeholder="<?php echo $entry_price_renew; ?>" id="input-price-renew" class="form-control" />
              <?php if (!empty($error_price_renew)) { ?><div class="text-danger"><?php echo $error_price_renew; ?></div><?php } ?>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><?php echo $text_demo; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_demo; ?></div>
          <div class="form-group col-sm-12">
            <div class="col-sm-6">
              <label for="input-demo-catalog"><?php echo $entry_demo_catalog; ?></label>
              <input type="text" name="demo_catalog" value="<?php echo $demo_catalog; ?>" placeholder="<?php echo $entry_demo_catalog; ?>" id="input-demo-catalog" class="form-control" />
              <?php if (!empty($error_demo_catalog)) { ?><div class="text-danger"><?php echo $error_demo_catalog; ?></div><?php } ?>
            </div>
            <div class="col-sm-6">
              <label for="input-demo-user"><?php echo $entry_demo_user; ?></label>
              <input type="text" name="demo_user" value="<?php echo $demo_user; ?>" placeholder="<?php echo $entry_demo_user; ?>" id="input-demo-user" class="form-control" />
              <?php if (!empty($error_demo_user)) { ?><div class="text-danger"><?php echo $error_demo_user; ?></div><?php } ?>
            </div>
            <div class="col-sm-6">
              <label for="input-demo-admin"><?php echo $entry_demo_admin; ?></label>
              <input type="text" name="demo_admin" value="<?php echo $demo_admin; ?>" placeholder="<?php echo $entry_demo_admin; ?>" id="input-demo-admin" class="form-control" />
              <?php if (!empty($error_demo_admin)) { ?><div class="text-danger"><?php echo $error_demo_admin; ?></div><?php } ?>
            </div>
            <div class="col-sm-6">
              <label for="input-demo-pass"><?php echo $entry_demo_pass; ?></label>
              <input type="text" name="demo_pass" value="<?php echo $demo_pass; ?>" placeholder="<?php echo $entry_demo_pass; ?>" id="input-demo-pass" class="form-control" />
              <?php if (!empty($error_demo_pass)) { ?><div class="text-danger"><?php echo $error_demo_pass; ?></div><?php } ?>
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend><?php echo $text_image; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_image; ?></div>
          <div class="form-group col-sm-12">
            <label for="input-thumb"><?php echo $entry_image; ?></label>
            <br>
            <img src="<?php echo $thumb; ?>" alt="" title="" id="image-thumb" class="img-thumbnail"><br>
            <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image">
            <button type="button" data-event="image-browse" data-target="#input-image" data-thumb="#image-thumb" class="btn btn-primary"><?php echo $button_browse; ?></button>
            <button type="button" data-event="image-placeholder" data-thumb="#image-thumb" data-placeholder="<?php $no_image; ?>" class="btn btn-danger"><?php echo $button_clear; ?></button>
            <?php if (!empty($error_thumb)) { ?><div class="text-danger"><?php echo $error_thumb; ?></div><?php } ?>
          </div>
          <div class="form-group col-sm-12">
            <label for="input-banner"><?php echo $entry_banner; ?></label>
            <input type="text" name="banner" value="<?php echo $banner; ?>" placeholder="<?php echo $entry_banner; ?>" id="input-banner" class="form-control" />
            <?php if (!empty($error_banner)) { ?><div class="text-danger"><?php echo $error_banner; ?></div><?php } ?>
          </div>
          IMAGE PLACEHOLDER
          <input type="hidden" name="images" value="" />
        </fieldset>
        <fieldset>
          <legend><?php echo $text_download; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_download; ?></div>
          DOWNLOAD PLACEHOLDER
          <input type="hidden" name="downloads" value="1" />
        </fieldset>
        <fieldset>
          <legend><?php echo $text_tracking; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_tracking; ?></div>
          <div class="form-group col-sm-12">
            <label for="input-ga-tracking"><?php echo $entry_ga_tracking; ?></label>
            <input type="text" name="ga_tracking" value="<?php echo $ga_tracking; ?>" placeholder="<?php echo $entry_ga_tracking; ?>" id="input-ga-tracking" class="form-control" />
            <?php if (!empty($error_ga_tracking)) { ?><div class="text-danger"><?php echo $error_ga_tracking; ?></div><?php } ?>
          </div>
        </fieldset>
        <fieldset>
          <legend><?php echo $text_status; ?></legend>
          <div class="bg-info text-info col-sm-12"><?php echo $help_status; ?></div>
          <div class="form-group col-sm-12">
            <div class="row">
              <div class="col-sm-6">
                <label class="control-label"><?php echo $entry_status; ?></label>
              </div>
              <div class="col-sm-6 text-right">
                <div>
                  <?php if ($status) { ?>
                  <label class="radio-inline"><input type="radio" name="status" value="1" checked="checked"><?php echo $text_yes; ?></label>
                  <label class="radio-inline"><input type="radio" name="status" value="0"><?php echo $text_no; ?></label>
                  <?php } else { ?>
                  <label class="radio-inline"><input type="radio" name="status" value="1"><?php echo $text_yes; ?></label>
                  <label class="radio-inline"><input type="radio" name="status" value="0" checked="checked"><?php echo $text_no; ?></label>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-right">
            <a href="<?php echo $cancel; ?>" class="btn btn-link"><?php echo $button_cancel; ?></a>
            <input type="submit" value="<?php echo $button_save; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script type="text/javascript"><!--
$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>

<?php echo $footer; ?>
