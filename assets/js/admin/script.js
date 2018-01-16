(function($){
  $("#wsip_search_images_button").click(function(){
     var $content = wsip.post_content;
     $content = $($content);

     $images_source = $content.find("img").map(function() { return this.src; }).get();

    var row = `
      
    `;

     $.ajax({
        url : wsip.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
              action : 'wsip_search_images_by_url',
              images_source : $images_source
            },
        success: function(response)
        {
          var row = "";
          $.each(response, function(key, obj){
            img_src = obj.guid;
            img_title = obj.post_title;
            row += `
              <tr valign="top">
                <td><img src="${img_src}" width="250px"></td>
                <td>${img_title}</td>
              </tr>
            `;
          });
          $("#wsip_list_post_images tbody").append(row);
        }
     });
  });
})(jQuery)