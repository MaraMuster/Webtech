function getCurrencies(){

  var from = document.getElementById('from');
  var to = document.getElementById('to');

  var req = new XMLHttpRequest();

  req.onreadystatechange=function(){
    if (req.readyState==4 && req.status==200){
      var obj=JSON.parse(this.responseText);
      var options = '';

      for(key in obj.rates){
        options=options+ '<option>'+key+'</option>'
      }

      from.innerHTML=options;
      to.innerHTML=options;
    }
  }


  req.open('GET', 'http://api.fixer.io/latest', true);
  req.send();

}

function convert(){

  var from = document.getElementById('from').value;
  var to = document.getElementById('to').value;
  var amount = document.getElementById('amount').value;

  var result = document.getElementById('result');

  if(from.length>0 && to.length>0 && amount.length>0){

    var req = new XMLHttpRequest();
    req.onreadystatechange= function() {

      if(req.readyState==4 && req.status==200){
        var obj=JSON.parse(this.responseText);

        window.obj=obj;
        var fact=obj.rates[to];

        if (fact!=undefined){
          result.innerHTML=parseFloat(amount)*parseFloat(fact);
        }
      }
    }

    req.open('GET','http://api.fixer.io/latest?base='+from+'&symbols='+to,true);
    req.send();
}
}
