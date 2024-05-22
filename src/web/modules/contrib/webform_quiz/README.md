### About this Module

Build quizzes using the Webform module.

### Installing the Webform Quiz Module

1. Copy/upload the webform_quiz module to the modules directory of your Drupal
   installation.

2. Enable the 'Webform Quiz' module and desired sub-modules in 'Extend'. 
   (/admin/modules)

3. Build a new quiz (/admin/structure/webform), click the "Add quiz" button.

### v2 updates
- drupal 8.8+ support
- Checkbox support fix
(https://www.drupal.org/project/webform_quiz/issues/3225190)
- new elements : Date, Select, TextField, ImageSelect (as submodule),
WebformQuizWizardPage
- lots of refactoring and bug fixes
- All elements build support multiple values to as correct answer, TextField
supports multiple answers by ';;' separator, Date now has no UI for multiple
answers
- WebformQuizWizardPage is for wrapping up each question into seperate
step/page and pretty css-ing

The code is not very pretty and annotated/commented but is working in
production
You can see real working quiz as example at
https://www.codpa.org.ua/30_rokiv_nezalezhnosti
If will be further interest to module from dev/users side I will find to time
to extend and prettify it.

Developed for www.codpa.org.ua
