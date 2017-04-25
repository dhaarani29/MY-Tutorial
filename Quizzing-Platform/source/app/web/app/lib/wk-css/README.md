# WK CSS

WK CSS is a self service, à la carte, CSS framework. It is open and freely available to all projects within Wolters Kluwer. Developers and designers can take only the parts of the framework needed for a project. It also provides a Bootstrap compatibility layer.

## Installation & Usage


### Bower

WK CSS is published and made available as a [Bower package](http://bower.io/). This is facilitated through the [Private Bower Repository](https://bower.wolterskluwer.io/).

In order to bring WK CSS into your project as a Bower package you must first configure Bower to search the Private Bower Repository.

This is a simple step which requires the creation of a `.bowerrc` file in the root of your project. For more information on configuring Bower, please visit their [official documentation](http://bower.io/docs/config/).

Login to [bower.wolterskluwer.io](https://bower.wolterskluwer.io/) with your NA credentials. Once logged in, copy the contents of the generated `.bowerrc` file into your project root or desired location. Below you will find a sample configuration.

```json
{
  "registry": {
     "search": [
       "https://bower.wolterskluwer.io/registry/UNIQUE_ACCESS_TOKEN",
       "https://bower.herokuapp.com"
     ]
  }
}
```

If not already present, please add a `bower.json` file to the project with dependencies. For example add this then do `bower install`.

```json
{
  "name": "example",
  "version": "1.0.0",
  "dependencies": {
    "wk-css": "~0.9"
  }
}
```

### Bamboo

Bamboo is a product development framework used within the GPO. The Bamboo package manager offers support of Bower packages allowing WK CSS to be configured within the Bamboo package descriptor file.

Add the following to your projects bamboo package descriptor file.

```json
{
  "dependencies": {
      "bower:wk-css": "0.9.*"
  }
}
```

Then install the dependencies via the command line with `bmb install`.


## Contributing

### Project Requirements

* [Node.js](https://nodejs.org/)
* [npm](https://www.npmjs.com/)
* [Bower](http://bower.io/)

### Build Requirements
* [Ruby](https://www.ruby-lang.org) - v1.9.3+
* [Sass](http://sass-lang.com/) - v3.4.1+
* [SCSS-Lint](https://github.com/causes/scss-lint) - The linter for scss files.
    * Requirements: Ruby, Sass, SCSS (not SASS) syntax
    * Lint your SCSS by running `gulp lint:scss` task or by `scss-lint .` command in the root of the project
    * Configuration options can be found in `.scss-lint.yml`
    * Editor Integration: [WebStorm scss-lint plugin](https://plugins.jetbrains.com/plugin/7530?pr=phpStorm), [Sublime scss-lint plugin](https://sublime.wbond.net/packages/SublimeLinter-contrib-scss-lint), [Vim Syntastic plugin](https://github.com/scrooloose/syntastic), [Atom scss plugin](https://atom.io/packages/linter-scss-lint)

### Project Setup

#### Clone `wk-css` Repository

Press `Clone` button in right top corner to see your personalized .git URL. Clone this repository:

    $ git clone https://stash.bw-sl.com/scm/bcc/wk-css.git
    $ cd wk-css

#### Setup `.npmrc`

Visit [npm.wolterskluwer.io](https://npm.wolterskluwer.io) and follow the instructions on setting up a local `.npmrc` file in the project root. This is required before an `npm install`.

#### Install Dependencies

In order to install dependencies run the following commands:

    $ npm install && bower install

### Building and Development

Show which commands are available to you.

    $ gulp help

#### Quickstart

Develop on localhost:

    $ gulp server

Build for distribution (builds every flavor):

    $ gulp build

### Build File Structure

    wk-css/
    ├─ dist/
    │  ├─ assets/
    │  │  ├─ images/
    │  │  │  └─ favicon/
    │  │  └─ styles/ (for CDN pages)
    │  ├─ docs/ (sass doc)
    │  ├─ fonts/
    │  ├─ samples/*.html (CDN sub pages)
    │  ├─ index.html, components.html (CDN top level pages)
    │  ├─ standard.css + min + map
    │  └─ with-bootstrap.css + min + map
    ├─ docs/
    │  ├─ assets/ (CDN pages & docs/local/)
    │  ├─ samples/
    │  │  ├─ **/_*.jade (partials and code highlight markup)
    │  │  └─ *.jade (CDN sub pages)
    │  ├─ sassdocs/
    │  ├─ local/ (local playground to demo, not copied to dist/)
    │  │  ├─ assets/
    │  │  └─ *.jade
    │  └─ index.jade, components.jade (CDN top level pages)
    └─ .tmp/
       └─ docs/
          ├─ assets/
          │  └─ styles/*.css
          ├─ local/
          │  ├─ assets/
          │  │  └─ styles/*.css
          │  └─ *.html
          ├─ samples/*.html
          └─ index.html, components.html


### Quality Control

#### SCSS Linting
The SCSS authored for this project will be [linted](https://github.com/causes/scss-lint) and must conform to the linting rules defined in the `.scss-lint.yml` file. If a specific SCSS Lint rule is not supplied, the [default configuration](https://github.com/brigade/scss-lint/blob/master/lib/scss_lint/linter/README.md) is implied.

The supplied linting rules should be pursued at all times though special circumstances may require slight deviation. A handful of inline exceptions which disable or modify the SCSS linting rules are present throughout this project.

#### SASS Unit Testing
Currently we have 2 types of SASS unit tests available:

* *Functions, utilities* tests with the help of [True](https://github.com/ericam/true)
* *Mixins* tests based on top of the [SassUnit](https://github.com/penman/SassUnit) ruby gem.

To be able to run *Mixins* tests you should have ruby and bundler installed. So after you have Ruby available in your PATH, run: `gem install bundler`.

After that the SassUnit gem should be installed (it is specified in the Gemfile):
`bundle`

Now you're able to unit test SASS:

* `gulp unitTest` runs both *Functions, utilities* and *Mixins* tests.
* `gulp unitTest:true` runs *Functions* and *utilities* tests.
* `gulp unitTest:mixins` runs *Mixins* tests only.

##### Writing new Unit Tests

Unit tests are concentrated in `test/sass` folder.

To write *Functions, utilities* unit tests with [True](https://github.com/ericam/true), please refer to `test/sass/tests.scss` file and follow the code examples already provided there.

In a few words, *Mixins* are being tested by compiling a `.scss` file and comparing it's corresponding `.css` file with the help of `git diff` command. So to create a new one, you should add `your-test.scss` file with mixin itself and `your-test.css` with expected output.


## Documentation

### SASS Doc Annotations
SASS Doc [annotations](http://sassdoc.com/annotations/) should be provided for all SCSS authored for this project. These annotations should be as detailed and complete as possible to ensure those who which to use and customize the WK CSS project have excellent documentation to support their efforts.


## Useful notes and links

### Managing Icons
We use [fontello](http://fontello.com/) service as icon fonts generator and .
wk-icons are stored in `src/wk-components/fonts/wk-icons` folder, while icons component located at `wk-components/_icons.scss`.

##### Adding new icons

* `npm install -g fontello-cli` install [fontello-cli](https://github.com/paulyoung/fontello-cli)
* `cd src/wk-components/fonts/wk-icons` Navigate to wk-icons folder
* `fontello-cli open` Opens a browser-tab with selected icons at fontello.
* Choose icons, click 'Save session'
* `fontello-cli install` Will generate new icons in the same folder.
* `Replace` old icons with newly generated.
* Copy new icons from wk-icons.css to `wk-components/_icons.scss` to make classes known to sass.
* TODO: watch [this issue](https://github.com/paulyoung/fontello-cli/issues/16) resolved to make process even simplier.
