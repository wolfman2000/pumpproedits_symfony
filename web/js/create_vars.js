var isDirty; // has the work changed? Should a prompt for saving take place?
var notes; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var width; // compliment to columns
var measures; // How many measures are in play?
var height; // compliment to measures
var tarea; // text area containing the notes.
var editID; // the edit ID. Normally not used until submitting.
var songID; // the song ID.
var songData; // the song data in JSON format.
var sync; // how much syncing are we dealing with?
var note; // which note are we using right now?
var style; // which style are we playing with? single, double, halfdouble, routine
var player; // Which player are we dealing with for routine steps?
var title; // what's the name of the edit?
var diff; // What's the difficulty rating of this edit?
var steps; // How many steps?
var jumps; // How many jumps?
var holds; // How many holds?
var mines; // How many mines?
var trips; // How many trips? (or hands)
var rolls; // How many rolls?
var lifts; // How many lifts?
var fakes; // How many fakes?
var badds; // Which are the problem notes?
var mX; // mouse position at X.
var mY; // mouse position at Y.

var selMode; // is the user inserting arrows or selecting rows? Can't have both!

var b64; // The encoded string for the end.
var captured; // Should input be captured instead of letting it go?

var SCALE; // How much of a zoom factor is there?
var ADJUST_SIZE; // common operation: size = ARR_HEIGHT * SCALE
var MEASURE_HEIGHT; // height of measure = ADJUST_SIZE * BEATS_PER_MEASURE

const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)

// These constants may change later, depending on how much spacing is wanted.
const BUFF_TOP = ARR_HEIGHT;
const BUFF_LFT = ARR_HEIGHT * 2;
const BUFF_RHT = ARR_HEIGHT * 2;
const BUFF_BOT = ARR_HEIGHT;

const BEATS_MAX = 192; // LCD of 48 and 64
const MEASURE_RATIO = BEATS_MAX / (ARR_HEIGHT * BEATS_PER_MEASURE);

const EOL = "\r\n"; // mainly for file parsing/saving.

/*
 * Test that an object is empty.
 */
function isEmpty(obj)
{
  for(var prop in obj)
  {
    if(obj.hasOwnProperty(prop)) return false;
  }
  return true;
}

/*
 * Add a capitalize function for the first letter.
 */
String.prototype.capitalize = function(){
   return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

/*
 * Repeat a string the specified number of times.
 * Takes advantage of Kris Kowal's bit manipulation.
 * http://blog.stevenlevithan.com/archives/fast-string-multiply
 */
function stringMul(str, num)
{
  var acc = [];
  for (var i = 0; (1 << i) <= num; i++)
  {
		if ((1 << i) & num) {acc.push(str); }
		str += str;
	}
	return acc.join("");

}

/*
 *  Base64 encode / decode
 *  http://www.webtoolkit.info/
 */
 
var Base64 =
{ 
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
 
	// public method for encoding
	encode : function (input)
  {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
 
		input = Base64._utf8_encode(input);
 
		while (i < input.length)
    {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
      
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
      
			if (isNaN(chr2))
      {
				enc3 = enc4 = 64;
			}
      else if (isNaN(chr3))
      {
				enc4 = 64;
			}
      
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
		}
		return output;
	},
 
	// public method for decoding
	decode : function (input)
  {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
    
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
    
		while (i < input.length)
    {
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
      
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
      
			output = output + String.fromCharCode(chr1);
      
			if (enc3 != 64)
      {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64)
      {
				output = output + String.fromCharCode(chr3);
			}
		}
		output = Base64._utf8_decode(output);
		return output;
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string)
  {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++)
    {
			var c = string.charCodeAt(n);
      
			if (c < 128)
      {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048))
      {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else
      {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext)
  {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length )
    {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224))
      {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else
      {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	}
};
