/**
 * Created by poovarasan on 22/8/15.
 */
var tabl;
$('#selectAll').hide();
var xmlhttp = false;
if (window.XMLHttpRequest)
    xmlhttp = new XMLHttpRequest();
else
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

function showTypes(str) {
    if (str == "Reset") {
        document.getElementById('SearchPagingDiv').innerHTML = '';
        document.getElementById('CategoryListID').value = '';
        document.getElementById('AttributesList').innerHTML = "<div  style='color: grey'> Attributes:	<p> Select the Category Type </p></div>";
        document.getElementById('FinalResultDiv').innerHTML = "<div style='color: grey'> Search Results: </div>";
    }
    if (str == "") {
        document.getElementById("CategoryTypeandLocation").innerHTML = "";
        return;
    } else {
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("CategoryTypeandLocation").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST", "ArtefactCategoryTypes.php", true);
        xmlhttp.send();
    }
}

function getAttributes(str) {
    //alert($("#categoryList").val());

    $('#selectAll').show();
    $('#checkAllBtn').prop('checked', false);
    document.getElementById('FinalResultDiv').innerHTML = "<div style='color: grey'> Search Results: </div>";
    document.getElementById('SearchPagingDiv').innerHTML = '';
    var type = document.getElementById('categoryList').value;
    if (str == "") {
        $('#selectAll').hide();
        document.getElementById("AttributesList").innerHTML = "";
        return;
    } else {
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("AttributesList").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST", "attributesList.php?type=" + document.getElementById('categoryList').value, true);
        xmlhttp.send();
    }

    $('#checkAllBtn').change(function () {
        if ($(this).is(':checked'))
            $('.checkall').prop('checked', true);
        else
            $('.checkall').prop('checked', false);
    });

}

function startSearch(str) {

    var length = document.forms.length;
    var data = [];
    var checkedBoolean = [];
    for (var index = 0; index < length; index++) {
        if ('columnValuesForm' == document.forms[index].id) {
            var formData = document.forms[index];
            var evenCount = 0, oddCount = 0;
            for (var i = 0; i < formData.length; i++) {
                if (i % 2 == 0) {//Even indexes are Check Boxes
                    if (formData[i].checked)
                        checkedBoolean[evenCount++] = true;
                    else
                        checkedBoolean[evenCount++] = false;
                }
                else {//Odd indexes are Datas
                    if (formData.elements[i].value != '') {
                        data[oddCount++] = formData.elements[i].value;
                    }
                    else
                        data[oddCount++] = 'NULL';
                }
            }
        }

        if ('categoryTypeID' == document.forms[index].id) {
            var catID = document.forms[index].elements[0].value;
        }

    }
    $("#FinalResultDiv").html("");
    $.ajax({
        url: "SearchResult1.php",
        data: "dataArray=" + data + "&ID=" + catID + "&checkArray=" + checkedBoolean + "&type=" + document.getElementById('categoryList').value,
        success: function (data1) {
            if (data == 'problem')
                alert('Please Select the Category');
            else {

                $.jsontotable(data1, {id: '#FinalResultDiv', className: 'table table-hover datatable1'});
                $('.datatable1').DataTable({
                    "scrollX": true,
                    "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
                    "pageLength": 5,
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "aButtons": [
                            {
                                "sExtends": "printpdf",
                                "sButtonText": "Pdf",
                                "fnClick": function (nButton, oConfig) {

                                    startPrint();
                                }
                            },
                            {
                                "sExtends": "xls",
                                "sButtonText": "Excel"
                            }

                        ]
                    },
                    "language": {
                        search: '<div class="input-group ip"> _INPUT_  <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'

                    }


                });


                $('#print1').on('click', function () {

                    startPrint();

                    //console.log($('#datatable1').tableToJSON());
                    //convert(data,length)
                });


            }
        }
    });


}


function startPrint() {

    var siz = $('.checkall:checked').size();

    if (siz > 5 || siz == 0) {

        $.growl.error({message: "Please select any 5 attribute to print..!", size: 'large'});
        return;
    }

    var length = document.forms.length;
    var data = [];
    var checkedBoolean = [];
    for (var index = 0; index < length; index++) {
        if ('columnValuesForm' == document.forms[index].id) {
            var formData = document.forms[index];
            var evenCount = 0, oddCount = 0;
            for (var i = 0; i < formData.length; i++) {
                if (i % 2 == 0) {//Even indexes are Check Boxes
                    if (formData[i].checked)
                        checkedBoolean[evenCount++] = true;
                    else
                        checkedBoolean[evenCount++] = false;
                }
                else {//Odd indexes are Datas
                    if (formData.elements[i].value != '') {
                        data[oddCount++] = formData.elements[i].value;
                    }
                    else
                        data[oddCount++] = 'NULL';
                }
            }
        }

        if ('categoryTypeID' == document.forms[index].id) {
            var catID = document.forms[index].elements[0].value;
        }

    }
    $.ajax({
        url: "SearchResult.php",
        data: "dataArray=" + data + "&ID=" + catID + "&checkArray=" + checkedBoolean + "&type=" + document.getElementById('categoryList').value,
        success: function (data1) {
            if (data == 'problem')
                alert('Please Select the Category');
            else {
                //$.jsontotable(data1, { id: '#dummyResult' , className: 'table table-hover dummyTable' });
                $(".dummyResult").html(data1);

                var doc = new jsPDF('p', 'pt');

                var imgData = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFYAAABZCAYAAACkANMiAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAACeNSURBVHhe7X35W1TXlnb/a/3j93zdt2/nJlGjzAgoaDQmMZ0Yzb2JSW4Sb2LirDigDKKIQAE1UHNBFQUU8zzPk0wO4Px+7zqndnEsmSShb/f3eHz2U1TVqTO8511rr70m/wVvty1B4F+25KhvD4q3wG4RCd4C+xbYLUJgiw77lrFvgd0iBLbosG8Z+xbYLUJgiw77T2HsS97Mi5cvIa+vbc+f4cHiIkZm59A2PoXqgRG4ewZh7+rXhrt3EEF+1jY+qe0j+4K/id7WPMcWgWk87H8rsC8JpgBq3B4tLaFjYhplHQM4U92CL521SDf7EW/yIsEcQKKtBon2WiQ6+MqR7Agi2c7PrT4kljmw3+rGMbcfZ2vqUd7Vg87JKSzymMZNe4hR591qbP9bgI1m5sKjR/D1jeA3AplhrkZiRT0S3S1IcDYivqIOMeV+7DT5EFvmw85SD4cbcWUexJe5+bcT20sdiDO7uJ8Du8xOxDt8SPL4kVZZjVSXFx9WOHCmthaVAwOQcxm3FaVkC1DeUmBfYcmLFxozL9S2Yy/BTHI2I87eiJ1lAewyVRK4Sr568e5dAlbqJaheDdCMCj/22/3YVeriewcOufw4VlnDvysQV25HWoWLzK5AktnGY5mxq7wcCRU27PZ4sKeSIDtsuFxfRyZPUP+8iEC41QzeEmBF6iLMeP4ctUNj+NbXSLEOkV1N+KBUwKxCbGklWVipvW4v8eAgRb2sdwQfmFwE2o2/FNnxj9pmZLV24d0iK4cF11s70Ts3jz8VmnA8UIOeuTnEm63YUVaGr/x+fOpxk8mlSLSU8bMixFhMZLITezxW/FjtRWh4kDr5uQawXONWAfyHA2sUtdaxSXzna0ISwYy11SOGYMaQnfFlVdhtCRAoN74NNON8Qyf+z20bfgg2oWlqBidqmgmqDX8ptuFrfx3y2rvxXrEF20wWXG5uQ3B8AilWO4p6ehEYG+ODKkWqzUrA53CjvQXvmu5in8OKi80N+MhtI4vvUCcXYbfXjPQqAdiF9vHRZfauPI3+LgXxhwKrQJ178BAXKfJJBFMBurPEh/eKPNhWTPG068z80x07vvSFUEsV8W8FVrKxG1eaO9F6bxYHnVUEz0014EVp7wC2lZgJoAU3O7pwrbUNBd3dyG1vx63OTrxvKoZ7eAhXWpqQ39mOPxXlI7OlHoU97bjZ2YJrbQ2Ulru8njuIs9xCiq8UGRxXGwOYvX8/wt7fhWTUj/8QYI0sDfSPYH8FZ3FXCycgP2JKKrGtyItPXCH4R6eRwhn/3wvsqBgYJWDDyGrpRnBsCgccfr4fxMXGNvwWaoZjcASm3n4C10PA27HdVI7YcgtyCOaXlZVomZ7GNwE/8jrakNncCHN/L8HugHtkkIy9xYczhdMNQSRYCxGaHMUeRxFS7IU46rci2XaLE2Y+0qvLcNhXgprBHgN7/xh4fzewSkc9ffIEWaEOTkpNFL1qDcwEzu5JBPJ9MvWot55gjcFMayCh3IsfKfZF3QP415smqoBG5Hf04gJBlUlpu8mKz7xVBNuDv1YFOCmV4wPq0F0cseVltAjKsMduQWqFmfs58UsoyHPexe6KEpT2deHX+gDK+jvxXY1bA/VUQxU+9pbCO9qPnM4QLIOd2O8u5P55SHblIyNQhJwmP+QeNN37B5hmvwvYFy90rs4s3Mc3XoobTabYUj9B9eCrykaU9Y0ip60P/1Ho4MRUjeKeIZg46ifv4euqEO5299MCcFDfujSRf7/EwmHGeyVleKfIRNEnS81m6kv+3unEQZeDf9s0QGPLi2l2FeKdklv4z5Kb3K+QTCzCAXcZfmvw4zOfBf9WdA3WwS58XlkOx3A32WrGf5ou41SjF3ldIewwX8Uh7x184ivE/toi/BQ0UzXMa/f04uWyBbEZDm8aWGXoD07dwyEa8HH2Bs7kVdhZQqZypncPc4KxVJGNzRTtNv7tQx11abLFg49dAZysayJznbQAbASxnCy14BO3D6fr5YH0o3FqGiMPHmD+8WMsPnuGJ5zJZSw+e4q5x0v87j4apydgHujB+aYairQV8dYCvFeag+1luZysbmGv4w4utgTwZVU5KoY68WfTJerbamS2+jVAUxw5+LamHCcb7DjovYm9gXwcrSrE8L3J3w3upoBVTO2ZmEIGV0YxVgJLk2mXGPWlPuwweXCNurOoexBn6tuoJ/s0k+l0fSvtUwfBrKC5VKax8zNPFe5wxdQ3P49nBjvzTVny7MVzHmMGd3tbyUwLYq252Gm+QbBzkEyRN/W14GyzDxdaKlE9TkmxXkGqIxufVhbgC38h9jqvY48zC3uqcnC48ib6pnSrYbPMfWNgFVP7ydR0W5B2YhBxBFSMezHsY2jY76Jhv63YgeP+es7sXm22/zHYwJndxmGlHrXRPq2naTX92hJXjv88vPRVS1HRedFDvtP3fbHCMV6g+d4Yda0bSRU52Gm5xskrDz+H7NSvrTgWKOYK7wrSHNc5oV1DqlMfac6rBPgqFxZZBDwXA1Njm9a5bwSsmv0n5xZwoIKgWoNkqACpAxpDQGO4Qootc5G1DnxXXY+Crj58FwjRLjVrDP0+yFUQnSfGTQEZDZ6aSNTnIinPOVYCWvkhBGjj1j03hX8Q0BjLFerUS7QGrmE3wUxzZJGxAuhVDdA05xXscRFs52XsdXFUXcMXVbmYnJvZlDm2YWDVTCkOji/oCImlSRWnsdNDID0aoNooc2pDRF4x9N8LaTfa3agc1Rmgi5jOuPXAVN9HqwYlOdoqbw1Gq98FJvrwsS+fKuKSJvZGQAVMfWRij/syAb6EdI6M6iyu7m5FnDpvYi1sGFjtRjhOVtYhwS0TlYDpJoA6S42AxpbZtXV8vNnOGb4cv9TVa5OQEVAjG6NBU5JhvJHHT54i2DOBio4JPHr81CCir7tVjA9Df4A6ixceL+J0E9lrPU99usxQATTNRVAJpoy9br66LxLcizhQdwPna0u1e4/2zK01D2wIWHXAMk5Au730QNEOFZYKqLHlOqjCUAE0tozOETpFYstFn5ajsHvZ+H7OyUm8sGut0TUwDW6+4ZkHmHu4hGe0CIbvLSDT2YKUvFp0TS7oDyqsGla6SfVg5HxybrWZ+uupY8+TtZkU+2VA9xBIAXRveKS7L2Cf9yIONWTD1lUTIcZagKrv1gVW8WGYy8wUaxXiLfRElZOlAiZBXQaUoJZXkKUV/M5KG9QM74g+s8pNLYvu+r5RBdbQ7AP86mrFg4cPBWulRFAQ6sX+ogb6Up5H1MlqNxvNXgWwf7yLvt3zFH8BUph6ITL2us4j3XOBn59DBl/3VV7ER1WX6VjXzbBVXPSvXMKGgH1JYL6m7RlP3RrHiUlAjaMNqjNUBzSODI3nkGXnB1wpBcfHtROJCRVhzgZWNLKvuvkz1Z24XdPGJ/NE18naAxKWvkBGcTWG6LDRWbu+MW+8BjHNZAtN9RHcs1QDBFEb5wmmjHNhUAXgswT3HPbXXMaP/nwIFhvx6a4JrGKZu7uPKqBOA1MfYbEPAxpXbuVnNNDN4oEywTcysilQNTZowOqs/spdj7zalsixFOBj9x/hiLkSM/QXrBSVWIu96hwK3MB4JyMUp5CuAaqDmi6DgOpD/hbmnsPH9Vl00DduSCWsy9jFpcc4aKP3vsJHRgqgoj91hipA4whoosWCd0uKqVO7Nw2qAkQB6OkfxW4uNuZ5DWqbX1xCXmMnarp68WyJ8a4NMlb9fiXmlg7Qwqn4heAJsDqg8qqPM9jrOYN9/Gy//yK+8F3BEld+622rAqvYamrtwG5fDUFd1qFGQOO4lhdQxXV3olZX8NokFRb7jZoo0SaTAvdOUzu+tlfSWT6Kafpbm4fHMHpvBksPH0TOs5pJthHmqvOcbi5Fgv2kxkwNUIIpI51DPssgwALuoYYsWLqq12Xtmox9TG/PARtDH3aPFv4wAhpPQOPNDINYyumxN2EvvU3zfJKaaIZ13kZAfd00el2DjU1MoGd4FA8Zv3oh+jEcAVBibXyIGzmn+p1iu/xm4ckjHKrMZMyMaiEM6LI6EIDPLrPWuz5rVwRWsdVFD32qr1oDNZ4xpXiL6FEdUG0w/JFk5ZrfVAjf6HCErUZzaS2RMYJgtBGXnj7FCB3Qk7QGwg60Vw7zlE6ZQfoWpvj9ayIe1tPriWoEXMNkGZjoIIl+1gBMJ0OXARbWyiSmj0P11+Drb9JZu8pUtjpjecK/ujxI4ki0ENQoQBMspQS1lHGlu/iGsSRt0qEhvlHGGAFRoA5R1H+pCeJTux3HXW786vPhTmMjRuZm9ZsgU2+2teJDmw1/oxvxYiAAJyMI82GAIybdRlA17KNPgLQs+PpTfQEthZM6uIahVINmIQQz8X1Vnrb/attrwEbcgffu0UHhICstFHezNoShAmgCA3QJlhK65krwAeNJbfTWK91qFLP12GpUG6ExCSIW4nunHQ0dHZieGMfC7AymeB2N/X3o4fuv/Yy8ljEMQ7CnGOt6wAcxwzEwPoaJ+bllvRde5m4EX0UEpWs7Zoc1YDNkEgtPXMugLlsJB6syMTTDyK888BUAXgFY/XIKmpuRVlVF57HlNUAF1CQN1AIcr/ZEDv6mk4iwRH4zR92ZSEd1ZpUH9xkofEbdHs2FI276a20mDPb24ommIpb3kGN4e7sweX8hbH6tb9caQVfXrZa+P9XfYQD0JIHVJzFdJSxbC/u9FziJ3UBRe+Wq6mBFVSBG8DG3k2rAzgBcOZkpLC1h/EhYWqxFPMVbv70sH/6xIQ0E9cQ3qgqMC4Gb7fU4bCvELJ00xuM8Dxvy/uE+Rgquo7S+Bs8f66BHwAhPlFMP76OgJRR+yPoDe5NrURInq6rgZCfiqWs1C0EBGjbB0r3nkUFgM6ov43jVTW3BsNL2CrDqQqY4MaQS0ARbGUE0McLJEQY00SrRzruMdhYwDFKqefR1kd74jSh1oZ2P41hlKUyhAF4+faaBJlaFAuYxJ7JDrkKc8piRGXRFFgRGy0Mx7UrIh7H5sD7ewGosmrXqvEvPnuDTKjrCXae1xYKAq63GBGiCupc+hD18/dCTiakF/XzRD3FFYAMD/XT2OslKE0EUlhZx3OUo5HsJwhViR3ke4/ZBXbeGRXqjDNH0UvjGl54+wRfO2+gY6HsFVMVc50A7PrRmY4p+h18DFtxbUDGp5Qcpqyg5992OOji6mjY1kaoHqfy5V9vpnRO7Nrwa28PFwx7+LWOvh54vzyUcrMtGcKhDBzZKeb0CrDIdcptC2BNwEkBh6jKgEpdPshXw8wJ8YM5hiGMwvAR9M51mfMIPnizhb+7bmJiaitjA+iwtbAa+prf/RqBCnA7IbHCiYajnFT1qtEbs/S3ICbkjx9mIDyFajNXipmaym6bXr5ozRgG6R5gqg16wDM9lHAjlIb/Vt6KeXVHH/lTlQorXgt3Uo8kVeqKDAJpku833txnuyKfFcAtTiw9embhWVDarfKjETth2ovIuJmemI+5CBcjMo/s4SG9/KycmURF5vAkbWalLic5SBax85hhowcVqc/j7N1NN6mErtTK9uEBQLyJNY+gyoGmaa5HuRjrEM4LZ+CVYsr6O1USUxvd/Ocuxm/ozmSK/DOgtvr/FsMYtxDFA90VVaUQXboYZEduR57zR4ED3OCfBsD2pVEvjZD+OMC61MDOnfXebs3BRk84QLdYVVifPwqqotLcOl5iEod2HmO4b8KZFo6IeuAB8LHgTyc6zmo9WAE2jEzyNLkbNKe6+gr3+6zjiu6lhFr29xtj7NH3SqUeTmDWSTJEXMPWRz3ETqfab2GW5zpCxS78Bg1/gTRgb+S3/aJ8cRKCnWQdMcw3qbPSNtOGE9xaeL+mJFNdbHChvrtLPG14669aFvn8Wv8+ro9rQgH9zxkZYG35gp5rMdM7QZ0AgFaBpjIelcqQR2FTPNWYzZmGBfotVgVVW4Thn1bSKO0zuvU1xF9HXAZWskd32PAbg8qhfryK7IxgWQ923+aabEmP1OjI1/poENE724WffLU3XCkNPB4vQOdAbsQyW7U/96v9efQv2Vn1CVcC/6XXpD0V/ULldzIS0ndaCiwrQVAYcUyWs485Ciusawzc3MD53TzuN0faOMFbZY/1MVkj3FjOZgcA6KPphQHfbcwlsDqObjNdbLsMUXisrMdzMDSiGqN8aTSi5MTF7rtaWYY6Gf8/0CFndhKUHeiKxUWTl/cLSQxx2XkLXoFgX/PeG5p+6Bjmv3JNspQPMkBTGuq+SpTqgEoRMkVC5iwFJ9w3sq8pH37Tu1DfatMvAho/cOTGKjMpiHoBZeQ4yNQxoij2bDM4msNnYZc2Efahd+8Vm/APGm1DgKn2oXtUkcp9iNkAHz8PFR3hCv6yatdV+6n1glMlyjst4OMtYWNiq2IyOVfckr/bhVuyynaMVoIfJBVB9MHROpqa5c7A/UMCkZj0EZTS5DMDqRO6YGGGM5y4PlEcgczUwteG4wcFsEb7GWC7CNaLbb9FxfAXaRl+NgL4C7grr/WjwjRPgL6FCZMnE9YRiHGb0Wtew6nkN9+QaaSewEtGl2IcBTWHofDdHipOYOHOQ4b+D9gnds7cmsIL+fuYvpRHYVOY2KUBTtQSHLMbks8jYC9rT/L2MXe3GjUyL1sXG75Q6mHwwy5zbs2jt6dQcTsaJbaVzRD8gJTXqVZHFPtzGexVrQIDUAd1NYu2m1EreV6r7JoG9a2Ds8tlW0LET2Oe7zYNRn7qyNTD1oYvDXopFDMWjlCFk2X6vjl0L3JVANX6mAMhqc+B7dy6W5mR2Fv26vqm1rHKW8wXUZ0rHmvpZI2GjeUV2KkB3E9BkIZyLatLJHFtmKvYxOU97KIZl9Gs6dpyJZek8UKqWJHYjAqieOULbja+x1rPMM636XVbBRlVF9H4RQMLg3Xs4j3T7BVS3Mcj3XAd0PbtaPZwHTAIZf7CIiYeLuM+/1efKKsjurMYHVppXFHkFaDIn72RaRikENcmRzznnNsZWSENawY59yIRfAkv6p2nJYjqg+tDzmuIrzuJUk0W7599jx24GXOXZUmw93WDBD57beDQzr+vWsP261sSlwJ9ZfIy68RlmlN/DPQYp1USoHsyvjU6qApm0BEwd0GSanEm05ZNpMSU5C5BOW3/BEMlQ9/QasM+5ijjizWdSWDZBVKDqeU2y4hBjOdlxDseq81+7EHVQo7iqjEA9K3DZa6X2eRNwjZaA/K55cohJJJlo6+rQogursXU1lTL1aAl9VB/ds/c11hofioD7RaCECwTRqcuAJtnJVK4+kx30mdAs/Ywr1A2tvOSCfwkyia06hyBKfpMYx8a8JlHmF+hOuwRZT2usjQrJKEN5NdYYQd2oSWQ0wwSApSePsd+Vhzy6Eh8vMFPGID3RQBrTPY3X9vjZc8xxVSdDkpr1B6PvIX6QFOdN5hwIqDcJpg5oIhdNSXauSO13sJuZ4j/SWbXStqJ3K7/Fx2QwzngeAVZPFFN5TZKKk861c0LFbxAPkFyMMS9KA8CwnBxbmEN2Ww3y2moRGO7FzKPl5d9KJs9KF2ncT53rl5ATXzkLMTPGFVuU+C/ryhWSgWQVt4avVtnl1ROD+MAibKXIhwFNpAMqkaKfyJXpbhaL7AnakdusO4Wig4or+mPFx3iwNpfAihdHz7xTuU2ShpNB11k8XWris5Qt2h+rGDzIpV6i5SZO0Eld2d6MQHc7XCwPahjuh/hhZYs2raKBNYKqMr7vdjUjlcftYcLdMzrCjSpAe9BG5wv/7pmdZtZ4E36p9eCHgB2naly42VyDpjHan2HzbPlh6Kuui63V2G6hNUB2GgFNoGMq0U6Pn70YaX47AlzpRd+HvF8R2On7c/hQ8kTpzFWZd8a8pnQ6fVNdZ+hlv8oIAkMlBoeIfmP6xZ2o9eIntxWLk0wF4qyrxbIpchMzM+hghaCKrkarBiOY6qIVqN6hPmbi3EKwpRmP6TAyqgAjoPeZJVPMB/m518qobhF+9FhQzOqa+q5O9LMmbJS5Ci4W23n69cwdPS9Md9xIVORDTzHiWLaURHYKQwVQDdQKOv0rmBFuL+HCycQIgu54j1ZpK4e/efBvuQbOCFwha8VbrmfeqbwmyRSReFC8/RdUT9Ao5z8lXrqVQPuWAH7qtKCuoz2SSKZdeNhVIZmCo9NTGCfIKwG5ElMDI0MsSSqEoyGExXkGDsOAGEV7lmBfa2nkbF2GI/ZyFNcF0d/fT6thFs8588uDVce+x9n8iNPKqnyGhMIqTY7ppwN/u1l0qw6mNhiOSuADSmDJUyIBTWQJ6TGPfWMxL6OuKO6o0vJC9zM/9PVEMQkNn9MimRLR1H4XnsCUqbJIUf/cbsMQsw5Xm1jkdxJq6WUo+1E4MdnIXpX1LfvZCc6u0hLY6kP0B8xqOs2YHrrIySyHOQepVjO+dlagioye5bmfPmT2jIAZ1jHyqtif2dSIz6wWPOM+xmXwcaqKHaxgTKKTXwEabyNLGaaSkWQvQ2rAi9ushDRiZlRjrzFWXYDEzA8wJ1TEXsu284ZzmlRImDF3ib1LDF5i8bIZHSQC0HceHwYmJiNuPqPIGPWigNrJmNYwc7LUtszCl/TDtiGeNV+exiYNVBH5yPf829Y3gDSbHZ/ZKuBj2H6e53zKBhHRIXL5jVIXzv5B1uqyqK6pCS+op9XnrTOTnLRkkioiiDKWAY23lfJ9GQcDAXx4g8x50ElldBjqd7BmJsz3/pvM+mAIQrLwJFJpSBRTuUwC7I+hgoj332gl3G5ph59lm8bPjIw0XpR8PsS0zNDAIPMDdMthmnVeR5nilMHsxkaWej6SkiXDTXRNz+Awm0CklFpRUhvCvdFRPJH8rvA+6lxG5oueym/rYm2ZGXeCtbg/cy8Svpf9vwl6WAQiE5bOThlxjFTHMQVARoKkA3hcTDGlo38FQBUxVs7dCguO5CcdYk6o6FM9AffVtBvJFJFUHMl38o+H3YgyCYR9odMUsfwGPb9VE1tDWNtoa8r+kRovtiFpGRxixwwpv3fiB2clZkeG8dKQOvmQDD8basWO4gqcclehn0kciwsLeKpleOvnMqoJdbMtrIg87A4i0WSHnYV6Cwxgqn1lH9/oEN5nEkoig6ivAqqzNM7GbCCqjhQmsrh69BKA1eoS1sk2fMxZ9YqWF7qcy2RMFtOBlgy9j3yXMM+MPSXi6oRNo+OoG1wuYdfVwcr1qs8Jqn9kAp84a5DKngW5/loUBUOwtvdiYP4+nlJkfazH3ceU/a+Y2tnKGf655BGsUZ45+2gRDubZHvOGkGxy44wngD6C8oi6XTFZrlkyJfcw1hdjEdHX2RnHhD8ZsVaWnkqKlZVJgU4nPWl2SCbmWtuqwCpgLHREfMycUAFWckR11uopNyoxVxidQAvhVJNpmZ2GYF99/zDcnf0Ynn+AJ1ztRDbus0DndcvELLKb+phCVIv9rGy8XlWHAbJwSVhIdo7RejhfxfYmpgA+KGTDCCYjF1U3orJ7CK1TnPxm7mujc3oeodF7sPWM4lpDD457GnHIUo0jfBC5rDnr5jEfUfTlAUV8DuHFwolQNd6jxSFJKgpUBWgcWRrLHLZ4qw3JVdUwtenSuRpb19ax4btf4o0d8V19hbWRxFwt1VEHWPRwXMXPDNnoyccqiUKdfIT60MSGD+crW3DS04KfXM04ztYlR8x1OGquwSmW3TtY/T06NMQlqtiG+oRwjxUzmTV9SCkM4jc24uno6kYji5uLGzpw1d/Ez0I4Ya/D39lL5nvWnv3A8auzjg+nERWN7ehiXe7CFCczSVQWsyp8X8JSpX4K6cf9CwueRX/qLNUZqg8LWcxSAGZcxjncOGB3MaN7bbauC6wCpZJ595J/H9GxXj2FXKyFNKbh7AtbDmKCJTp+pb7VowvR4IJBujlmB3ayF0E9Wz21ssHDKE2th1wZMZglv4iQWcqNfvN2Iy43iKPFQdRwIrw/PamttHS6kPlPGapZfIjHDxaoY+e08ZjV28+5bH7Jmi5m171WjKF0e2TBwbDP+2xxImDK2MmS/R1Suk+xF0BjCWishUnXFjt2VwWpW3vXZeu6wGr6UAbF5SdWRu+vyYxMZPuYHCap5d/X3aYtW6gtcfdpCQ7nGIs/g7op/QIUuNoNRWDT/3jJyWaO/tCZB48xNPsItQMzyA4O4POiJqRkV+NvRUF4WeQ8PznGeBfNJwPbtOuKOt5qb40TpZGpwQm2PTGbCCCTqCnuAuheBxv6eLz4CxtPxBHQGMlkJ6hxtD7+xonyD6ma0YHVL390doq1TpmMSnIiI6iypD3svwbnSDPDxB58H7qDWIKbwWVwGv0J4lqsYkWKbGqGfu0GCezABP2hXSMoDfUhp7IDeb422Os60EsRfkCb8qkAGkZwpaVv9DFXe6+R3GD/ekdHCGQp20sx+Y/s3MHuR6nMB75K/ZnL9ign6hqYTUkVYGWVUIWH0WovRrh6U2Rb75muaRWoHyuVUMHqPMkLlWq9VNdZHA3m4lyrBebBemR1uPieCR1avdQlvl4ki8+ipD8UuQZ9ARFm6yuOEnLxOUX8KZecMljX9VJWckaGRtmm692YBoD6Tfjhqt/c6enG+6XCVH2mf591aWl2Jxv1dLKPDGtu2d3jp7pGAs4CQfb3SmbTivL2Lu3na01YxmvaELCRA/JCL9SV4kD9dU3sEx2n8Yk/i+DVUiXc1RIc0um8kVQcyW0SX+4um0QbbDRnwvkAhjL4CLvCYv2aqjC4A41MXA/U1ZbFc5yI/xGqwzsmGv3hWX5HmQXpnJR+4ALjFvsmfOhiM4rGVubjkq1slpbgq8NJdlJSIfX1zq2+3zCw6ulLjdM31LcZwaua2ItrMabiFL6pvYMyJjjkdwdYfZJNtl4gwIyRuSX4eIF2bh4nNV3vqgdl9KOuJ9Lr3VD0719ZbfHHlVyVif78C1tJidkUw2KVd1jr+18+Pzt0tOJySweb87TiH3XN+HOxle5OFmE7q/E5KzLFMjJKwHrXIt9vGFjtwGHhnGLA8XN2othLt6EwNNlxAT83liOzzUW10Eg/LXVuHesU7JfoEGZjBfd1Oi6uEuBMnKi3oWtOr0lVm2rmEA3OejewEpiv+GJ5gE76Fr6rqcG7BHRHOWsprCwAlCJqTkjfsnfCmcYWgtnEPgr1+LG2iaDaWCXkwS52qPuQHeom2TxNv/c3294IWONTG5geoz82m60+mL/EFJxdDDB+GSxAQW8NjjA7JKczgKPVd/mdsFeyo2/QG3ad4F6l641JdY0udsEY1atVDJsxjLIWoxUjVROJV4/xgv1ipqgn67RJ6b1SFqUQUGHpNvoV/lRcjr8GanGRxXn/t9CMSy2d7MbRyno1Kb52U335WbfmRz87gCjpejNY35Cx6uCKuf3sm3KYZTl7/DcIINuDMOdgu+Ucfqgvh22ojbq3ET+EWJTBvitJ9utsxCAhDQklM//LmkWQb/AhsPS+txl9CzOaabbZTezSXlbOSKOzz1jGtI2tpN4loPGaycSucyYzX+34O7t2Hq2q0ZpO5nawp1c79aozgP8oklJWir+dTh92++xlV0+dSJu7ojdmrDqNYpp0/DkauIN0WgR7KfLJTOz4PFCI40xmsxLcrA52yGT4+HSTR2t2I4AmMUCXwpi8xJNibLnYZs6mgyOfTW7MONNUTV3dRcaNY5iGv3QsEo9+dBejYXYxauCKqpSlSqca6ml7kmmsRXuHRdLbOMuLDSrjz2TnR+5K9uZq0kC929PP/VvZaiqAr6sbmFxdyb5ebtaxsVGQqw6H2MZqiKvEzTJV4bNpYPUT62I8y2zAE7Xl2Mf8KUkUS9Aa2OSQrTYcqTbhIJla2Cv9DbO1mPw+NhOLs+UxP0Ein7cZV2I+LsMfu6y3CXI+tpXncwVUoPlDUx2l2OeysJDExlGBDJeNIRHpUsSKyDITmVhCZrJHF0EVHSoMFR36Ptv1xVsdmsh/U83lMmtxP/HwofUN4STF/rC3lk3Q6CKULiEENYENLr5mO8DZsMtSRWs3x9dNqgLjyZS18IwxrRwmBe8P3kWKh/YsWRpLXXq4qgQFPY1kouSa5qKorxV3mGT8ZcBKe7dIAzSGgO5izyxxLidLPImA7mDd13aOXfQ2xbAUaqe5BNvLS/heJqFS1piJ3pReW1ZNf25ji9Ov/EH8jR06j/JV7NGPuFLKbO5AEZfOf/WHKPq9FPtqfh5kRaXoU7ZStfjZLrAe17jCe7ZCgPOfBmz0jFkz1M3WSkXYW1PCCesWmSX5CQX4lcDKuNPDOoGWIL6vdeMcq24uttTigKecqfesLmchiQB60MP2UfQ2fROsJNBmDdAE+kLTHBUaoPvEded2a4b9LrY4fY/6M53t+qwDwsZmnGRvxKKeAU5APjaSpNHPVn9fVIaYUM0+tGy5sq3ERbb6uExtwD7q1AC9b8vzx2ahfPV3v0sVRF+C0vNz1H9X2Stwr+TZkrGJjMvHWJmWwySH000BstiKayyau93dysmjkWC3M/U+iLxOed+siXw+s1suMih4iN76wz5OKlwl1bK5bgLraHM7OpHDZeceepqy2jpoLjVoduh3NJnkNZPjON2EOfTjHqO78WNPDRnqYTc66mGyNNZRS3VVy8VOK30VerRik3PUqk/hDwVWv8DlS+xgEvOPNU4WQZiRWknRpdjHsQInkUG6Y+zhepntR39i4dzZpjqNpVdaGftn9eHxoJ+vdThSVclasmacZe3sz6F6inQvQWvDdYJ5sbmVFkAvW+qJqdSCbPaY/XtNA7tz9uJzrpb20FxKsrITKB3mAuhO6Qsmvb4p9t9WNmjN0/9olhpR/sOB1U0UA7x0tNSz9eiP7IyZxsa4qX6W6DN8HMOivF0WNstlxDOpgqrA78WnPjcbPobwEVl6pbWFAFfjcmsrvmKVtzhGTjKcYmLg8CKb84phf6K2kev7bjIygP1OP35gQ98Ei1cDUyYmAXMnW5zEuxrI0BqG9BtQx0YS/ys7Hq80sem2ywt0TU3gciOjBE7qS7+LuU90ctjpTA47l8XTlExRl3X8ATeBof48zoDfqYZmWgKiEgLIcPo03ZnuqKS+ZZGfzUtmetmZ00ldyyaUFPldbFYZR8d5InVomtWPC2xa2cmkkf/1Pbpf172vajBJe6wa7Mfp2iAOMIycQoam+Flpzoa7cS52SWJX+B1s3iPLzu3sPfN+GdtBm9lskr29drIvzU4a9zvYmmonV0o7zOxWxwkowRNCkvQBp/7M4Mrp1+omNmsYYYrl/4dd5V8DWFKRopYyi5JTwGhpeVcXztWFcJS5CBk2Bxv62qmT2fSRUdokdpJPpEGf6CKAMrgySqD+TKDJlM7/D+EIzaczVANl7PopzJRjGje1NP5j5vuNHWVLdOx6pxYNrKcbrbAxLrX8P3dMIDg4DA/1qp0tqmS4aZMGGdrR/+eOef1/7lihMlDLbNHcjutdzdZ8/08Bdmtu5X/WUd8Cu0XP4y2wb4HdIgS26LBvGbtFwP4/SmaFPbCNEnEAAAAASUVORK5CYII="
                doc.addImage(imgData, 'JPEG', 20, 20, 50, 56);
                doc.text(80, 50, "Global Archive");
                doc.line(10, 90, 595, 90);


                var res = doc.autoTableHtmlToJson(document.getElementById("printTable"), true);
                doc.autoTable(res.columns, res.data, {startY: 110, overflow: 'linebreak'});
                doc.output('dataurlnewwindow');


            }
        }

    });


}
