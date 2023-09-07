## Optimize image

Optimize images by removing metadata, lossy compressing it to 80 quality and converting them to webp.
Also, large images are rescaled, default maximum width and height is set to 2560px.

### Run
```
php optimize.php directory=<ABSOLUTE OR RELATIVE PATH TO FOLDER>
```

Script automatically optimizes all images in selected folder, so make sure to input directory not file!

Optimized images are saved in sub-folder called optimized folowed by date in format 'Y-m-d_H-i-s'