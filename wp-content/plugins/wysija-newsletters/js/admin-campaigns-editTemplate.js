var modalLbox=null;var wysijaIMG={};var WYSIJAhtml="";var ajaxOver=true;document.observe("dom:loaded",function(){$("wysija-upload-browse").observe("click",function(){tb_show(wysijatrans.imgmediamanager,$("wysija-upload-browse").readAttribute("href2")+"&KeepThis=true&TB_iframe=true&height=600&width=800",null);return false})});function valueRemoved(a){saveIQS();$("wysija-img-"+a).remove();return true}function valueAdded(e,f){var d=new Element("img",{wysija_height:f.height,wysija_width:f.width,wysija_src:f.url,src:f.thumb_url});var c=new Element("a",{wysija_type:"image","class":"wysija_item"}).update(d);var b=new Element("li",{id:"wysija-img-"+e,"class":"new"}).update(c);$("wj-images-quick").insert(b,"before");saveIQS();return true};