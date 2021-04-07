# Wordpress register custom static page
Utility to register custom static pages in Wordpress settings page.
It will add an option to select a static page for custom purpose in Settings->reading that can be recall anywhere.

## Installation
Add functions to your wordpress theme

## Use
    m_register_static_page('page_id', 'Page Label');
  
where 'page_id' is a unique id for identify that page (eg: faq_page) and 'Page Label' is the label used as option in settings page and *post status* in pages list (eg: Frequent Questions Page).

To get the selected page ID to use the wordpress function 

    get_option('page_id);

To check if the current page is the selected one, use the included function

    m_is_static_page('page_id');
