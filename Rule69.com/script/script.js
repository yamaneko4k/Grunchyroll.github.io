const activePage = window.location.pathname;
console.log("Active page: ",activePage);
const navLinks = document.querySelectorAll('li a').
forEach(link => {
    if(link.href.includes(`${activePage}`)){
        link.classList.add('active');
        console.log("Linked",`${activePage}`);
    }
})

window.addEventListener("load", () =>{
  const loader = document.querySelector(".loader");

  loader.classList.add("loader--hidden");

  document.querySelector(".loader").addEventListener("transitionend", () => {
    document.body.removeChild(loader);
  });
});





document.addEventListener('DOMContentLoaded', () => {
    // Controls (as seen below) works in such a way that as soon as you explicitly define (add) one control
    // to the settings, ALL default controls are removed and you have to add them back in by defining those below.

    // For example, let's say you just simply wanted to add 'restart' to the control bar in addition to the default.
    // Once you specify *just* the 'restart' property below, ALL of the controls (progress bar, play, speed, etc) will be removed,
    // meaning that you MUST specify 'play', 'progress', 'speed' and the other default controls to see them again.

    const controls = [
        'play-large', // The large play button in the center
        // 'restart', // Restart playback
        // 'rewind', // Rewind by the seek time (default 10 seconds)
        'play', // Play/pause playback
        // 'fast-forward', // Fast forward by the seek time (default 10 seconds)
        'progress', // The progress bar and scrubber for playback and buffering
        'current-time', // The current time of playback
        'duration', // The full duration of the media
        'mute', // Toggle mute
        'volume', // Volume control
        // 'captions', // Toggle captions
        'settings', // Settings menu
        'pip', // Picture-in-picture (currently Safari only)
        'airplay', // Airplay (currently Safari only)
        'download', // Show a download button with a link to either the current source or a custom URL you specify in your options
        'fullscreen' // Toggle fullscreen
    ];
    

    const player = Plyr.setup('.js-player', { controls });

});

var style = document.createElement('style');
style.setAttribute("id","multiselect_dropdown_styles");
style.innerHTML = `
.multiselect-dropdown{
  display: inline-block;
  padding: 2px 5px 0px 5px;
  border-radius: 4px;
  border: solid 1px #ced4da;
  background-color: white;
  position: relative;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right .75rem center;
  background-size: 16px 12px;
}
.multiselect-dropdown span.optext, .multiselect-dropdown span.placeholder{
  margin-right:0.5em; 
  margin-bottom:2px;
  padding:1px 0; 
  border-radius: 4px; 
  display:inline-block;
}
.multiselect-dropdown span.optext{
  background-color:lightgray;
  padding:1px 0.75em; 
}
.multiselect-dropdown span.optext .optdel {
  float: right;
  margin: 0 -6px 1px 5px;
  font-size: 0.7em;
  margin-top: 2px;
  cursor: pointer;
  color: #666;
}
.multiselect-dropdown span.optext .optdel:hover { color: #c66;}
.multiselect-dropdown span.placeholder{
  color:#ced4da;
}
.multiselect-dropdown-list-wrapper{
  box-shadow: gray 0 3px 8px;
  z-index: 100;
  padding:2px;
  border-radius: 4px;
  border: solid 1px #ced4da;
  display: none;
  margin: -1px;
  position: absolute;
  top:0;
  left: 0;
  right: 0;
  background: white;
}
.multiselect-dropdown-list-wrapper .multiselect-dropdown-search{
  margin-bottom:5px;
}
.multiselect-dropdown-list{
  padding:2px;
  height: 15rem;
  overflow-y:auto;
  overflow-x: hidden;
}
.multiselect-dropdown-list::-webkit-scrollbar {
  width: 6px;
}
.multiselect-dropdown-list::-webkit-scrollbar-thumb {
  background-color: #bec4ca;
  border-radius:3px;
}

.multiselect-dropdown-list div{
  padding: 5px;
}
.multiselect-dropdown-list input{
  height: 1.15em;
  width: 1.15em;
  margin-right: 0.35em;  
}
.multiselect-dropdown-list div.checked{
}
.multiselect-dropdown-list div:hover{
  background-color: #ced4da;
}
.multiselect-dropdown span.maxselected {width:100%;}
.multiselect-dropdown-all-selector {border-bottom:solid 1px #999;}
`;
document.head.appendChild(style);

function MultiselectDropdown(options){
  var config={
    search:true,
    height:'15rem',
    placeholder:'select',
    txtSelected:'selected',
    txtAll:'All',
    txtRemove: 'Remove',
    txtSearch:'search',
    ...options
  };
  function newEl(tag,attrs){
    var e=document.createElement(tag);
    if(attrs!==undefined) Object.keys(attrs).forEach(k=>{
      if(k==='class') { Array.isArray(attrs[k]) ? attrs[k].forEach(o=>o!==''?e.classList.add(o):0) : (attrs[k]!==''?e.classList.add(attrs[k]):0)}
      else if(k==='style'){  
        Object.keys(attrs[k]).forEach(ks=>{
          e.style[ks]=attrs[k][ks];
        });
       }
      else if(k==='text'){attrs[k]===''?e.innerHTML='&nbsp;':e.innerText=attrs[k]}
      else e[k]=attrs[k];
    });
    return e;
  }

  
  document.querySelectorAll("select[multiple]").forEach((el,k)=>{
    
    var div=newEl('div',{class:'multiselect-dropdown',style:{width:config.style?.width??el.clientWidth+'px',padding:config.style?.padding??''}});
    el.style.display='none';
    el.parentNode.insertBefore(div,el.nextSibling);
    var listWrap=newEl('div',{class:'multiselect-dropdown-list-wrapper'});
    var list=newEl('div',{class:'multiselect-dropdown-list',style:{height:config.height}});
    var search=newEl('input',{class:['multiselect-dropdown-search'].concat([config.searchInput?.class??'form-control']),style:{width:'100%',display:el.attributes['multiselect-search']?.value==='true'?'block':'none'},placeholder:config.txtSearch});
    listWrap.appendChild(search);
    div.appendChild(listWrap);
    listWrap.appendChild(list);

    el.loadOptions=()=>{
      list.innerHTML='';
      
      if(el.attributes['multiselect-select-all']?.value=='true'){
        var op=newEl('div',{class:'multiselect-dropdown-all-selector'})
        var ic=newEl('input',{type:'checkbox'});
        op.appendChild(ic);
        op.appendChild(newEl('label',{text:config.txtAll}));
  
        op.addEventListener('click',()=>{
          op.classList.toggle('checked');
          op.querySelector("input").checked=!op.querySelector("input").checked;
          
          var ch=op.querySelector("input").checked;
          list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")
            .forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ch; i.optEl.selected=ch}});
  
          el.dispatchEvent(new Event('change'));
        });
        ic.addEventListener('click',(ev)=>{
          ic.checked=!ic.checked;
        });
  
        list.appendChild(op);
      }

      Array.from(el.options).map(o=>{
        var op=newEl('div',{class:o.selected?'checked':'',optEl:o})
        var ic=newEl('input',{type:'checkbox',checked:o.selected});
        op.appendChild(ic);
        op.appendChild(newEl('label',{text:o.text}));

        op.addEventListener('click',()=>{
          op.classList.toggle('checked');
          op.querySelector("input").checked=!op.querySelector("input").checked;
          op.optEl.selected=!!!op.optEl.selected;
          el.dispatchEvent(new Event('change'));
        });
        ic.addEventListener('click',(ev)=>{
          ic.checked=!ic.checked;
        });
        o.listitemEl=op;
        list.appendChild(op);
      });
      div.listEl=listWrap;

      div.refresh=()=>{
        div.querySelectorAll('span.optext, span.placeholder').forEach(t=>div.removeChild(t));
        var sels=Array.from(el.selectedOptions);
        if(sels.length>(el.attributes['multiselect-max-items']?.value??5)){
          div.appendChild(newEl('span',{class:['optext','maxselected'],text:sels.length+' '+config.txtSelected}));          
        }
        else{
          sels.map(x=>{
            var c=newEl('span',{class:'optext',text:x.text, srcOption: x});
            if((el.attributes['multiselect-hide-x']?.value !== 'true'))
              c.appendChild(newEl('span',{class:'optdel',text:'🗙',title:config.txtRemove, onclick:(ev)=>{c.srcOption.listitemEl.dispatchEvent(new Event('click'));div.refresh();ev.stopPropagation();}}));

            div.appendChild(c);
          });
        }
        if(0==el.selectedOptions.length) div.appendChild(newEl('span',{class:'placeholder',text:el.attributes['placeholder']?.value??config.placeholder}));
      };
      div.refresh();
    }
    el.loadOptions();
    
    search.addEventListener('input',()=>{
      list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector)").forEach(d=>{
        var txt=d.querySelector("label").innerText.toUpperCase();
        d.style.display=txt.includes(search.value.toUpperCase())?'block':'none';
      });
    });

    div.addEventListener('click',()=>{
      div.listEl.style.display='block';
      search.focus();
      search.select();
    });
    
    document.addEventListener('click', function(event) {
      if (!div.contains(event.target)) {
        listWrap.style.display='none';
        div.refresh();
      }
    });    
  });
}

window.addEventListener('load',()=>{
  MultiselectDropdown(window.MultiselectDropdownOptions);
});
