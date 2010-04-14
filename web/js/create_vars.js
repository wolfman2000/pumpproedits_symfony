/*
 * This file is meant for introducing global variables and functions
 * that are used by the other files. Do whatever is possible to keep
 * the number of global variables low.
 */
var isDirty; // has the work changed? Should a prompt for saving take place?
var columns; // How many columns are we working with?
var width; // compliment to columns
var measures; // How many measures are in play?
var height; // compliment to measures
var authID; // the edit author's ID. Once uploaded, it can't be changed.
var editID; // the edit ID. Normally not used until submitting.
var songID; // the song ID.
var songData; // the song data in JSON format.

var selMode; // is the user inserting arrows or selecting rows? Can't have both!

var captured; // Should input be captured instead of letting it go?
var clipboard; // needed for copying/pasting

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

// Test that an object is empty.
function isEmpty(obj)
{
  for(var prop in obj)
  {
    if(obj.hasOwnProperty(prop)) return false;
  }
  return true;
}

// Add a capitalize function for the first letter.
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
