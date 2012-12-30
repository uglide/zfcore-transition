<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * DeleteLinkFormatter.php
 * Date: 19.11.12
 */
class Core_Grid_Formatter_DeleteLinkFormatter
{

    /**
     * delete link formatter
     *
     * @param $value
     * @param $row
     * @return string
     */
    public function formatter($value, $row)
    {
        $link = '<a href="%s" class="btn btn-danger span1 delete">Delete</a>';
        $url = $this->view->url(
            array(
                'action' => 'delete',
                'id'     => $row['id']
            ),
            'default'
        );

        return sprintf($link, $url);
    }


}
