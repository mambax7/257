README
===========

- WAITING MODULE -

This module offers you an extensible waiting contents block into your XOOPS.

The original XOOPS block of "Waiting Contents" works only for official modules,
and is no longer extensible.
It is nonsense to have to do Hack for yourself when you use 3rd party modules.

By installing this module and adding proper plug-ins only,
you as webmaster can confirm any approval waitings of any modules at one view.

NEW FEATURE in 0.96

Update to XOOPS 2.5.0 ModuleAdmin GUI

NEW FEATURE in 0.8

- plug-ins for waiting can be placed inside module's directory

If you as module developper put your plug-in as modules/(your module)/include/waiting.plugin.php, waiting module will find it.
The plugin in module's directory has higher prioriy than the plugin in waiting's directory.

- multiple waitings can be returned from single function

You can return waitings multiplly with the format as follows:
[code]
array(
  array("adminlink"=>URL",
        "pendingnum"=>NUM,
        "lang_linkname"=>LINKNAME),
  array("adminlink"=>URL",
        "pendingnum"=>NUM,
        "lang_linkname"=>LINKNAME),
  array("adminlink"=>URL",
        "pendingnum"=>NUM,
        "lang_linkname"=>LINKNAME)
)
[/code]

If you'll return just one waiting, this format is also ok.
[code]
  array("adminlink"=>URL",
        "pendingnum"=>NUM,
        "lang_linkname"=>LINKNAME)
[/code]
It will be deprecated functions named b_waiting_(dirname)_X

- modified the template as watings belonging modules

This modification makes that plug-ins need not to return the information of the module. It is enough to display short message like "submitted".



This module is made by Ryuji (http://ryus.co.jp/)
If you can read Japanese, let's visit Ryuji's site!

PLUGINS:

-addresses (by gruessle)
-Agenda-X (by GIJOE)
-AMS (by karedokx)
-articles (by twilo)
-catads (by Alain01)
-CBB (by gravies)
-extcal (by alain01)
-MyAds (by Tom_G3X)
-myAlbum-P (by GIJOE) multiple
-mydownloads (by GIJOE)
-mylinks(by GIJOE)
-newbb2 (by gravies)
-news (by GIJOE)
-PDlinks (by flying.tux)
-PDdownloads (by flying.tux)
-piCal (by GIJOE) (>=0.8 has the module side plugin)
-pico (by GIJOE) (D3 module side plugin)
-popnupblog (by dashboard)
-simpleblog (by kousuke)
-smartfaq (by mariuss)
-smartpartner (by mariuss)
-smartsection (by flying.tux)
-system -- comments (by GIJOE)
-tutorials (by GIJOE)
-weblinks (by Tom_G3X)
-WF-downloads (by coldfire, flying.tux)
-WF-links (by flying.tux)
-WF-Sections (by GIJOE)
-WordBook (by AgD)
-WordPress ME (by nobunobu) multiple (>=0.5 has the module side plugin)
-xcGallery (by nao-pon)
-xDirectory (by GIJOE)
-xfguestbook (by karedokx)
-xfsection (by Bezoops)
-xyp4all (by flying.tux)
-yomi search (by nao-pon)
-eguide (by tes)