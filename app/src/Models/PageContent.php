<?php
declare(strict_types=1);

namespace GuzabaPlatform\Cms\Models;


use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Orm\Interfaces\ValidationFailedExceptionInterface;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\BaseActiveRecord;

/**
 * Class PageContent
 * @package GuzabaPlatform\Cms\Models
 *
 * @property int page_content_id
 * @property int page_id
 * @property string page_content_data
 */
class PageContent extends BaseActiveRecord
{
    protected const CONFIG_DEFAULTS = [
        'main_table' => 'cms_page_content',
        //the page content does not have own route - the creation and deletion of the records is done through the page route
        //there is no editing of content - each new revision creates a new record
        //'route' => '/admin/cms/page',//to be used for editing and deleting
    ];

    protected const CONFIG_RUNTIME = [];

    public static function create(int $page_id, string $page_content_data): self
    {
        $PageContent = new static();
        $PageContent->page_id = $page_id;
        $PageContent->page_content_data = $page_content_data;
        $PageContent->write();
        return $PageContent;
    }

    protected function _validate_page_id(): ?ValidationFailedExceptionInterface
    {
        if (!$this->page_id) {
            return new ValidationFailedException($this, 'page_id', sprintf(t::_('No page_id provided.')));
        }
        try {
            $Page = new Page($this->page_id);
        } catch (RecordNotFoundException $Exception) {
            return new ValidationFailedException($this, 'role_id', sprintf(t::_('The provided page_id %1$s does not exist.'), $this->page_id ));
        }
        return NULL;
    }

    protected function _before_save(): void
    {
        if (!$this->is_new()) {
            throw new RunTimeException(sprintf(t::_('The instances of class %s can no be updated.')));
        }
    }


}