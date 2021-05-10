# Sitegeist.Nomenclator

## A Glossary Package for Neos

This package provides the website with a glossary page, which gives the editor of the website the possibility to add a list of terms and definitions as glossary entries. The list of entries consists of terms or phrases, that appear somewhere in different pages of the site. 
After adding a term to the glossary its appearance in all binded contents gets linked to the glossary and is also provided with a modal box, which gives a short explanation about the term or phrase. A further click on the link in the modal forwards the visitor to a point in the glossary page, where the term is defined.  

### Authors & Sponsors

* Masoud Hedayati - hedayati@sitegeist.de

*The development and the public-releases of this package is generously sponsored
by our employer http://www.sitegeist.de.*

## Installation
## Usage
### Glossary Page as a Nodetype
After the installation of the package, the glossary page will be available as a 'Sitegeist.Nomenclator:Content.Glossary' nodetype. Every site must contain only one single glossary page. As a best practice, it is recommended to add the 'Sitegeist.Nomenclator:Content.Glossary' as an auto-created child node in the homepage and prevent the page from being created by editor.
### CSS and JavaScript
`Resources/Public/Styles/main.css` and `Resources/Public/JavaScript/main.js` are responsible for the layout of the glossary page and handling the click event on the terms. They must be manually linked by the integrator of the website.
### binding contents to the glossary entries
In order to search the terms in a content and link them to the glossary `Sitegeist.Nomenclator:LinkTermsToGlossary` processor must be applied to them.
For Example:
```
renderer = Customer.Site:Component.Test.Text {
    content = ${props.content}
    content.@process.linkToGlossary = Sitegeist.Nomenclator:LinkTermsToGlossary
}
```


