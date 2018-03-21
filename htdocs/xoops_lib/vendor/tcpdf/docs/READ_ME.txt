TCPDF_for_XOOPS Version 2.01 - 2014-09-13

1. Author(s)
--------------------------------
Initial author : Nicola Asuni - Tecnick.com LTD - www.tecnick.com - info@tecnick.com
Initial TCPDF version : 6.0.093
Programmers having been adaptation and optimization for Xoops
- montuy337513 (alias black_beard on xoops.org) - Administrator frxoops.org - www.chg-web.org - montuy337513@chg-web.com
- Philodenelle - www.chg-web.org - philodenelle@chg-web.com

2. Minimum required
--------------------------------
- Xoops 2.5.7
- PHP 5.3.17

3. Installing TCPDF_for_XOOPS
--------------------------------
Xoops 2.5.7 and higher
- unzip the file and copy the directory TCPDF to the directory ./xoops_lib/vendor/
- replace files makepdf.php for modules you need
- Check the following directories have write access
    * ./xoops_lib/vendor/tcpdf/fonts
    * ./xoops_lib/vendor/tcpdf/cache
    * ./xoops_lib/vendor/tcpdf/images
- For specifics characters (japanese, korean, chinese, taiwanese) download
  the specific font (see below) and copy the directory ./Frameworks/tcpdf/fonts or ./language/fonts
    * For Chinese character download : http://sourceforge.net/projects/chgxoops/files/Frameworks/chinese_simplified_font_tcpdf_for_xoops.zip/download
    * For Taiwan character download : http://sourceforge.net/projects/chgxoops/files/Frameworks/chinese_taiwan_font_tcpdf_for_xoops.zip/download
    * For Korean character download : http://sourceforge.net/projects/chgxoops/files/Frameworks/korean_font_tcpdf_for_xoops.zip/download
    * For Japonese character download : http://sourceforge.net/projects/chgxoops/files/Frameworks/japonese_font_tcpdf_for_xoops.zip/download

4. Update TCPDF_for_XOOPS
--------------------------------
Xoops 2.5.0 - 2.5.6
- This version

Xoops 2.5.7 and higher
- Overwrite existing files with new files in directory ./xoops_lib/vendor/tcpdf


5. Convert a font for TCPDF_for_XOOPS
--------------------------------
- Using the addTTFfont() method you can directly create a TCPDF font starting
  from a TrueType, OpenType or Type1 font.
  NOTE: The './Frameworks/tcpdf/fonts' directory must be writeable by the webserver.

  For example:
       $fontname = $pdf->addTTFfont('/path-to-font/DejaVuSans.ttf', 'TrueTypeUnicode', '', 32);
