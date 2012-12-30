<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * EditLinkFormatter.php
 * Date: 19.11.12
 */
class Core_Grid_Formatter_EditLinkFormatter
{

    /**
     * edit link formatter
     *
     * @param $value
     * @param $row
     * @return string
     */
    public function formatter($value, $row)
    {
        $link = '<a href="%s" class="btn span1">Edit</a>';
        $url = $this->view->url(
            array(
                'action' => 'edit',
                'id'     => $row['id']
            ),
            'default'
        );

        return sprintf($link, $url);
    }


}
