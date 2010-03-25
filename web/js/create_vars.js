var isDirty; // has the work changed? Should a prompt for saving take place?
var notes; // What does the internal note structure look like?
var columns; // How many columns are we working with?
var width; // compliment to columns
var height; // compliment to measures
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
var mX; // mouse position at X.
var mY; // mouse position at Y.

const SVG_NS = "http://www.w3.org/2000/svg"; // required for creating elements.
const ARR_HEIGHT = 16; // initial arrow heights were 16px.
const SCALE = 3; // scale everything by 2 for now.
const ADJUST_SIZE = ARR_HEIGHT * SCALE; // 
const BEATS_PER_MEASURE = 4; // always 4 beats per measure (for our purposes)
const BEATS_MAX = 192; // LCD of 48 and 64

// These constants may change later, depending on how much spacing is wanted.
const BUFF_TOP = ADJUST_SIZE;
const BUFF_LFT = ADJUST_SIZE;
const BUFF_RHT = ADJUST_SIZE;
const BUFF_BOT = ADJUST_SIZE;

const MEASURE_HEIGHT = ADJUST_SIZE * BEATS_PER_MEASURE; // the height of our measure.

function isEmpty(obj)
{
  for(var prop in obj)
  {
    if(obj.hasOwnProperty(prop)) return false;
  }
  return true;
}
