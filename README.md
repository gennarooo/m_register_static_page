# Wordpress register custom static page
Utility to register custom static pages in Wordpress settings page.
It will add an option to select a static page for custom purpose in Settings->reading that can be recall anywhere.
Support for Polylang.

## Installation
Copy/paste in your theme functions.php or include in your theme.

## Use
To register a static page

    m_register_static_page('page_id', 'Page Label');
  
where 'page_id' is a unique id for identify that page (eg: faq_page) and 'Page Label' is the label used as option in settings page and *post status* in pages list (eg: Frequent Questions Page).

### Useful functions

To get the selected static page ID, use the wordpress function:

	m_get_static_page_id('page_id');

In order to retrive the page ID you can also use the wordpress native function, in this case you'll have only the default language page if using Polylang:

    get_option('page_id');

To get the selected static page object:

	m_get_static_page('page_id');

To check if the current page is the selected static page, use the included function:

    m_is_static_page('page_id');
