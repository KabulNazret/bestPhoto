bestPhoto
========

IP.Board application that shows best photo of the week basing on repuration system for IP.Gallery


###Features
* Application shows the list of the best images per a week
* The beggining of every week the application saves the best image automatically
* The best image is choosen by the biggest quantity of likes during a week
* Additionally together with application will be installed hook that shows current best photo on (e32)CustomSiderbar
* English and Russian localization
* Settings: possibility of blocking images from writing into statistics


###Requirements
* PHP 5.3+ 
* MySql 5.1+
* IP.Board 3.3.4 or above
* IP.Gallery 4.2.1 or above
* (e32) Custom Sidebar Blocks 2.1.0 or above (if you want to display hook on the main page)


###Installation
* Extract all files from folder Upload to your forum root
* If you want to install russian language copy the files from Localization/{LANG_ID} folder to your forum root
* In your ACP go to 'Applications and modules' section and install new application


###Hook settings
* If you install CustomSidebar _after_ installing bestPhoto application, you need to set up hook settings manually:
<div>
  Hook type: Template hook<br />
  Skin group: skin_boards<br />
  Skin template: boardIndexTemplate<br />
  Hook type: foreach cycle<br />
  Hook ID: side_blocks<br />
  Position: outer.pre<br />
</div>
