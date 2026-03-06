# Migrator: Flux content type provider

This TYPO3 extension extends the `migrator` extension by providing a content type provider for Flux content elements, helping you to migrate your existing Flux content elements
to other content types, e.g. Content Blocks and/or Container content elements.

## Installation

```bash
composer require webcoast/migrator-from-flux
```

The extension has a dependency to the `migrator` extension, which will be installed automatically through composer. Furthermore, the `flux` is required, as this extension uses
the Flux API to fetch the content type configuration and the record data of the content elements. Therefore, the `flux` extension is also installed automatically through composer.

If you want to migrate Flux elements to content blocks you need the following packages:
* `webcoast/migrator-from-flux` (this extension)
* `webcoast/migrator-to-content-blocks` (content type builder for content blocks)

If you want to migrate to container elements, because you have some Flux elements with a grid configuration, you need the following packages:
* `webcoast/migrator-from-flux` (this extension)
* `webcoast/migrator-to-container` (content type builder for container elements)

## Compatibility

| Extension ↓ / TYPO3 → | 13.4 |
|-----------------------|:----:|
| 1.0.0                 |  ✅   |

## Content Type Providers

This extension provides a content type provider for Flux content elements, which uses the Flux API to fetch the content type configuration and the record data of the content elements.

This extension supports most of the standard TYPO3 CMS field types. Explicitly not supported are `password`, `none`, `passthrough` and `flex`. Section fields (anonymous inline records
without a database table) are supported but only with one container type. Multiple records types within the same section field are not supported.

## Upgrade Wizard (Record data migration)

This extension provides data as described in the Migrator core documentation. Files (from legacy file fields) and file references (from modern file fields) are provided as objects of
type `TYPO3\CMS\Core\Resource\File` and `TYPO3\CMS\Core\Resource\FileReference`, respectively, which can be used in the record data migrator according to the migrator core documentation.

## Sponsors

The development of this extension has been sponsored by
* [Aemka](https://aemka.de/)
* [apart](https://apart.lu/)
* [Homepage Helden](https://www.homepage-helden.de/)
* [HZ Internet Services](https://www.hziegenhain.de/)

Thanks to all sponsors for their support and contributions to the development of this extension!

If you are interested in sponsoring the development of this extension, please contact me via email to [thorben@webcoast.dk](mailto:thorben@webcoast.dk) or in the TYPO3 Slack channel
(#ext-migrator).

## Contributing
Contributions to this extension are always welcome, both in form of pull requests, bug reports and feature requests and ideas.

If you have questions, reach out to me via email to [thorben@webcoast.dk](mailto:thorben@webcoast.dk), the discussion section of this repository or the TYPO3 Slack channel (#ext-migrator).

## License
This extension is licensed under the GPL-3.0 License. See the [LICENSE](LICENSE) file for more details.
