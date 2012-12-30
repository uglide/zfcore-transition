<?php
/**
 * TextPreview.php
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * Date: 11.06.12
 */
class Application_View_Helper_TextPreview extends Zend_View_Helper_Abstract
{
    public function textPreview($text, $maxLength = 250, $end = ' ...')
    {
        $plainText = strip_tags($text);

        if (mb_strlen($plainText) > $maxLength) {
            $lastPos = mb_strpos($plainText, ' ', $maxLength);
            return mb_substr($plainText, 0, ($lastPos > 0) ? $lastPos : $maxLength) . $end;
        } else {
            return $plainText;
        }
    }
}
