

Link to Github repo [https://github.com/feimosi/baguetteBox.js]

#Customization

You can pass an object with custom options as the second parameter.

```
baguetteBox.run('.gallery', {
    // Custom options
});
```

| Option |	Type |	Default |	Description|
| ------------- |-------------| -----| :-------------------------:|
| captions | Boolean or function(element) | true | Display image captions. Passing a function will use a string returned by this callback. The only argument is a element containing the image. Invoked in the context of the current gallery array |
| buttons | Boolean or 'auto' | 'auto' | Display buttons. 'auto' hides buttons on touch-enabled devices or when only one image is available |
| fullScreen | Boolean | false | Enable full screen mode |
| noScrollbars |Boolean  | false | Hide scrollbars when gallery is displayed |
| titleTag | Boolean | false | Use caption value also in the gallery img.title attribute |
| async | Boolean | false | Load files asynchronously |
| preload | Number | 2 | How many files should be preloaded |
| animation | 'slideIn' or 'fadeIn' or false | 'slideIn' | Animation type |
| afterShow | function | null | Callback to be run after showing the overlay |
| afterHide | function | null | Callback to be run after hiding the overlay |
| onChange | function(currentIndex, imagesCount) | null | Callback to be run when image changes |
| overlayBackgroundColor | String | 'rgba(0,0,0,0.8)' | Background color for the lightbox overlay |
| filter | RegExp | /.+\.(gif|jpe?g|png|webp)/i | Pattern to match image files. Applied to the a.href attribute |




#API

    run - initialize baguetteBox.js
    showNext - switch to the next image
    showPrevious - switch to the previous image
    destroy - remove the plugin with any event bindings

The following options are available:

The first two methods return true on success or false if there are no more images to be loaded.

#Responsive images

To use this feature, simply put data-at-{width} attributes on a tags with a value being the path to the desired image. {width} should be the maximum screen width the image can be displayed at. The script chooses the first image with {width} greater than or equal to the current screen width for best user experience. That last data-at-X image is also used in the case of a screen larger than X.

Here's an example of what the HTML code can look like:

```<a href="img/2-1.jpg"
  data-at-450="img/thumbs/2-1.jpg"
  data-at-800="img/small/2-1.jpg"
  data-at-1366="img/medium/2-1.jpg"
  data-at-1920="img/big/2-1.jpg">
    <img src="img/thumbs/2-1.jpg">
</a>
```

If you have 1366x768 resolution baguetteBox.js will choose "img/medium/2-1.jpg". If, however, it's 1440x900 it'll choose "img/big/2-1.jpg". Keep the href attribute as a fallback (link to a bigger image e.g. of HD size) for older browsers.

#Compatibility

    IE 8+
    Chrome
    Firefox 3.6+
    Opera 12+
    Safari 5+