/*!	
* FitText.js 1.0 jQuery free version
*
* Copyright 2011, Dave Rupert http://daverupert.com 
* Released under the WTFPL license 
* http://sam.zoy.org/wtfpl/
* Modified by Slawomir Kolodziej http://slawekk.info
*
* Date: Tue Aug 09 2011 10:45:54 GMT+0200 (CEST)
*/

let enableSize = 1024;

(function(){

    let addEvent = function (el, type, fn) {
      if (el.addEventListener)
        el.addEventListener(type, fn, false);
          else
              el.attachEvent('on'+type, fn);
    };
    
    let extend = function(obj,ext){
      for(let key in ext)
        if(ext.hasOwnProperty(key))
          obj[key] = ext[key];
      return obj;
    };
  
    window.fitText = function (el, kompressor, options) {

      let settings = extend({
        'minFontSize' : -1/0,
        'maxFontSize' : 1/0
      },options);
  
      let fit = function (el) {
        let compressor = kompressor || 1;
  
        let resizer = function () {
          if(window.innerWidth > enableSize) {
            el.style.fontSize = Math.max(Math.min(el.clientWidth / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)) + 'px';
          } else {
            //Remove attribute
            el.removeAttribute("style");
          }
        };
  
        // Call once to set.
        resizer();
  
        // Bind events
        // If you have any js library which support Events, replace this part
        // and remove addEvent function (or use original jQuery version)
        addEvent(window, 'resize', resizer);
        addEvent(window, 'orientationchange', resizer);
      };
  
      if (el.length)
        for(let i=0; i<el.length; i++)
          fit(el[i]);
      else
        fit(el);
  
      // return set of elements
      return el;
    };
  })();