<?php
$root = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
require_once($root . '/wp-load.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title></title>
    <script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>
    <script type="text/javascript" src="js/emotions.js"></script>
    <style type="text/css">a{text-decoration:none}</style>
  </head>
  <body style="display: none" role="application" aria-labelledby="app_title">
    <div align="center">
      <table id="emoticon_table" role="presentation" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td>
            <?php
            global $wpsmiliestrans;
            $smilies = array_unique($wpsmiliestrans);
            foreach ($smilies as $smiley => $src):
              ?>
              <a class="emoticon_link" role="button" title="<?php echo $smiley; ?>" href="javascript:EmotionsDialog.insert('<?php echo $smiley; ?>');">
                <?php echo translate_smiley(array($smiley)); ?>
              </a>
            <?php endforeach; ?>
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>
