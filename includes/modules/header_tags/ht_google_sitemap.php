<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class ht_google_sitemap
  {
    public $code;
    public $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct()
    {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_header_tags_sitemap_title');
      $this->description = CLICSHOPPING::getDef('module_header_tags_sitemap_description');

      if (defined('MODULE_HEADER_TAGS_SITEMAP_STATUS')) {
        $this->sort_order = MODULE_HEADER_TAGS_SITEMAP_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_SITEMAP_STATUS == 'True');
      }
    }

    public function execute()
    {

      $CLICSHOPPING_Template = Registry::get('Template');

      if (!is_null(MODULE_HEADER_TAGS_SITEMAP_ID)) {

        $CLICSHOPPING_Template->addBlock('<meta name="google-site-verification" content="' . MODULE_HEADER_TAGS_SITEMAP_ID . '" />', $this->group);
      }
    }

    public function isEnabled()
    {
      return $this->enabled;
    }

    public function check()
    {
      return defined('MODULE_HEADER_TAGS_SITEMAP_STATUS');
    }

    public function install()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to install this module ?',
          'configuration_key' => 'MODULE_HEADER_TAGS_SITEMAP_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to install this module ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez-vous insérer le code de Google Sitemap (Gestion des cartographies) ?',
          'configuration_key' => 'MODULE_HEADER_TAGS_SITEMAP_ID',
          'configuration_value' => '',
          'configuration_description' => 'Veuillez vous enregistrer sur google webmaster pour réaliser cette opération<br /><br />Les noms de fichier à fournir sont (répertoire boutique):<br /><br />google_sitemap_categories.php<br />google_sitemap_products.php<br />google_sitemap_index.php<br /><br /><br />L\'URL de la boutique à enregistrer sur google sitemapp: http://www.maboutique.com/boutique.<br /><br />Veuillez insérer la clef donnée',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort Order',
          'configuration_key' => 'MODULE_HEADER_TAGS_SITEMAP_SORT_ORDER',
          'configuration_value' => '105',
          'configuration_description' => 'Sort order. Lowest is displayed in first',
          'configuration_group_id' => '6',
          'sort_order' => '85',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
        ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
      );
    }

    public function remove()
    {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys()
    {
      return array('MODULE_HEADER_TAGS_SITEMAP_STATUS',
        'MODULE_HEADER_TAGS_SITEMAP_ID',
        'MODULE_HEADER_TAGS_SITEMAP_SORT_ORDER'
      );
    }
  }
