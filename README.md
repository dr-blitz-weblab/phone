# TYPO3 phone functionality - Fluid link 


## What is it?

Adds phone number functionality to TYPO3  based on the  Google's [libphonenumber](https://github.com/google/libphonenumber).

INTERNATIONAL and NATIONAL formats are consistent with the definition in ITU-T Recommendation E123. For example, 
the number of the DR BLITZ WEBLAB  office will be written as "+48 12 333 44 01" in INTERNATIONAL format, and as 
"12 333 44 01" in NATIONAL format. E164 format is as per INTERNATIONAL format but with no formatting applied, 
e.g. "+48123334401". RFC3966 is as per INTERNATIONAL format, but with all spaces and other separating symbols replaced 
with a hyphen, and with any phone number extension appended with ";ext=". 
It also will have a prefix of "tel:" added, e.g. "tel:+48-12-333-44-01".


## Installation

It is recommended to use [composer](https://getcomposer.org) to install the library.

```bash
$ composer require drblitz/phone
```
## EXAMPLE 

### EXAMPLE 1 - NATIONAL format

```html
{wb:link.phone(class:'contact-item__link',phoneNumber:data.tx_projectcore_phone, region:'pl', format: 2,)}
```
return 
```html
<a class="contact-item__link" href="tel:+48-12-333-44-01">12 333 44 01</a>
```
### EXAMPLE 2 - INTERNATIONAL format

```html
{wb:link.phone(class:'contact-item__link',phoneNumber:data.tx_projectcore_phone, region:'pl', format: 1,)}
```

return

```html
<a class="contact-item__link" href="tel:+48-12-333-44-01">+48 12 333 44 01</a>
```

### EXAMPLE 3 - E164 format

```html
{wb:link.phone(class:'contact-item__link',phoneNumber:data.tx_projectcore_phone, region:'pl', format: 0,)}
```

return

```html
<a class="contact-item__link" href="tel:+48-12-333-44-01">+48123334401</a>
```

### EXAMPLE 4 - Using ViewHelper with CE Textpic.
```html
{wb:format.phone(value: data.bodytext, region:'de', format: 2)->f:format.html(parseFuncTSPath: 'lib.parseFunc')
```
