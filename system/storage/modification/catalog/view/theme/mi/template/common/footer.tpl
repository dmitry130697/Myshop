
        <?php global $registry; $bw_module_groups = $registry->get('bw_module_groups'); if (!empty($bw_module_groups['bw_ft'])) { ?>
        <?php echo implode('', $bw_module_groups['bw_ft']); ?>
        <?php } ?>      
      
<footer>
  <div class="container">

        <?php global $registry; $bw_module_groups = $registry->get('bw_module_groups'); if (!empty($bw_module_groups['bw_fct'])) { ?>
        <?php echo implode('', $bw_module_groups['bw_fct']); ?>
        <?php } ?>      
      
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<div class="greyfon">
	<div class="container footer2">
			<div class="col-sm-9">
				<?php echo $powered; ?> © Copyright 2016. All Rights Reserved.

        <?php global $registry; $bw_module_groups = $registry->get('bw_module_groups'); if (!empty($bw_module_groups['bw_fcb'])) { ?>
        <?php echo implode('', $bw_module_groups['bw_fcb']); ?>
        <?php } ?>      
      
			</div>
			<div class="col-sm-3 text-right snetwork">

        <?php global $registry; $bw_module_groups = $registry->get('bw_module_groups'); if (!empty($bw_module_groups['bw_fb'])) { ?>
        <?php echo implode('', $bw_module_groups['bw_fb']); ?>
        <?php } ?>      
      
			</div>
	</div>
</div>
</body></html>