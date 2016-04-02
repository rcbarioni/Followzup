/*
# ========================================================================================
#
#   FOLLOWZUP PROJECT
#   RICARDO BARIONI - MARÇO 2016
#
# ========================================================================================
#
#   Copyright (C) 2016 Ricardo Camargo Barioni
#
#   This program is free software: you can redistribute it and/or modify it under
#   the terms of the GNU General Public License as published by the Free Software
#   Foundation, either version 3 of the License, or any later version.
#
#   This program is distributed in the hope that it will be useful, but WITHOUT 
#   ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
#   FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with this program.  If not, see <http://www.gnu.org/licenses/>
#
# ========================================================================================
*/

  function savePosition(theForm)
  {
     if(theForm)
     {
        var scrollx = typeof window.pageXOffset != 'undefined' ? window.pageXOffset : (document.documentElement || document.body.parentNode || document.body).scrollLeft;
        var scrolly = typeof window.pageYOffset != 'undefined' ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
        theForm.posx.value = scrollx;
        theForm.posy.value = scrolly;
     }
  }

  function savePositionSelect(theSelect)
  {
     var scrollx = typeof window.pageXOffset != 'undefined' ? window.pageXOffset : document.documentElement.scrollLeft || document.body.parentNode.scrollLeft || document.body.scrollLeft;
     var scrolly = typeof window.pageYOffset != 'undefined' ? window.pageYOffset : document.documentElement.scrollTop  || document.body.parentNode.scrollTop  || document.body.scrollTop;
     theSelect.options[theSelect.selectedIndex].value = theSelect.options[theSelect.selectedIndex].value + '_' + scrollx + '_' + scrolly;
  }

  function savePositionHref(theHref)
  {
     var sx = typeof window.pageXOffset != 'undefined' ? window.pageXOffset : (document.documentElement || document.body.parentNode || document.body).scrollLeft;
     var sy = typeof window.pageYOffset != 'undefined' ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
     var scrollx = parseInt(sx);
     var scrolly = parseInt(sy);
     theHref.href = theHref.href + '_' + scrollx + '_' + scrolly;
  }

  function toggle_visibility(id,newstyle) 
  {
     if      ( newstyle == 'block' )                                 document.getElementById(id).style.display = 'block';
     else if ( newstyle == 'none'  )                                 document.getElementById(id).style.display = 'none';
     else if ( document.getElementById(id).style.display == 'none' ) document.getElementById(id).style.display = 'block';
     else                                                            document.getElementById(id).style.display = 'none';
  }
