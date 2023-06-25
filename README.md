# SF Linkhandler

With `linkhandler` a handful of LinkHandlers will be registered
in your TYPO3 system. You can use them in RTE (`rte_ckeditor`) in
link fields and everywhere where `t3://` is allowed.

## Registered LinkHandlers

### Packagist

With this LinkHandler you can create links to Packagist. Add `vendor` and
`packagist` and this LinkHandler will start a request to Packagist API
and retrieves the download stats for given package. If response is valid
the download stats will be added to link text.

Example: `t3://packagist?vendor=stefanfroemken&package=mysqlreport`

Result: `<a href="https://packagist.org/packages/stefanfroemken/mysqlreport">MySQl Report <span>(Total Downloads: 2649)</span></a>`
