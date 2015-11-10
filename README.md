====
CSRF checker for the Eve Framework
====
====

- [Install](#install)
- [Usage](#usage)

====

<a name="install"></a>
## Install


====

<a name="usage"></a>
## Usage

1. Add this in public/index.php towards the top of the bootstrap chain.

```
//CSRF
```

`'1234567890'` is the escape key you use when writing tests for pages using this plugin.

2. For each route, determine whether a CSRF ID should be generated and/or checked as in
 
```
'/product/create' => array(
	'method' => 'ALL',
	'make_csrf' => true,
	'check_csrf' => true,
	'class' => '\\Eve\\App\\Front\\Action\\Product\\Create'
),
``` 

3. In each form template add `<input type="hidden" name="csrf" value="{{item.csrf}}" />` before the form tag.

4. Done ;)