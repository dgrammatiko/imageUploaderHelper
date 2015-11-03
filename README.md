# imageUploaderHelper

Image upload helper is a dead simple plugin that will ensure your images are stored in a sensible way and also their size will be otimum for your site.

## User Guide
Download the installable file from the releases link and install as usual.
Go to plugins and enable this plugin.

## Settings
#### Initial folder name
This is the first folder after your /images folder.
Initial value is uploads and will creat a structure like: `/images/uploads/2015/10/21/image.jpg`
Deleting this value will make a structure like: `/images/2015/10/21/image.jpg`

#### Maximum width
Any image that exceeds this width will be resized to this width. Image ratio is retained.
Any image bellow this width will NOT be processed.
Default value is 500 (pixels)

#### Image quality
This controls the compression of the image.
100 means top quality, no compression.
Default value is 75

#### Pass through name
A word that if prepend in the image filename (followed by an underscore) will prevent any resizing.
Default value is raw and will reflect on images filenames like `raw_filename.jpg`
Deleting this value will disable this feature.

### This is another set and forget plugins

