(function($) {
   'use strict';
   
   $(function() {
      const random_shortcode_ids_include = document.querySelector('#random_shortcode_ids_include');
      const random_shortcode_ids_exclude = document.querySelector('#random_shortcode_ids_exclude');
      
      random_shortcode_ids_include.addEventListener('change',function(e){
         $('#random_shortcode_included_ids').attr('readonly', false)
         $('#random_shortcode_included_ids').removeClass('disabled')
         $('#random_shortcode_excluded_ids').attr('readonly', true)
         $('#random_shortcode_excluded_ids').addClass('disabled')
      })
      random_shortcode_ids_exclude.addEventListener('change',function(e){
         $('#random_shortcode_included_ids').attr('readonly', true)
         $('#random_shortcode_included_ids').addClass('disabled')
         $('#random_shortcode_excluded_ids').attr('readonly', false)
         $('#random_shortcode_excluded_ids').removeClass('disabled')
      })
   })

})(jQuery);

function scribit_selectText(el) {
   if (document.selection) { // IE
      var range = document.body.createTextRange();
      range.moveToElementText(el);
      range.select();
   } else if (window.getSelection) {
      var range = document.createRange();
      var node = document.getElementById(el);
      //range.selectNode(el);
      range.selectNode(node);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);
   }
}

function scribit_clearSelection() {
   if (document.selection) { // IE
      document.selection.empty();
   } else if (window.getSelection) {
      window.getSelection().removeAllRanges();
   }
}

function scribit_copyContentToClipboard(el, confirm_text) {

   scribit_selectText(el);

   /* Copy the text inside the text field */
   document.execCommand("copy");

   /* Alert the copied text */
   alert(confirm_text);

   scribit_clearSelection();
}