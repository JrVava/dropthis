const $ = require("jquery");
const Typeahead = require("../../src/jquery.typeahead");

describe("Typeahead searchOnFocus option Tests", () => {
  "use strict";

  let myTypeahead;

  describe("Typeahead searchOnFocus is defined, the source should be searched when focused", () => {
    beforeAll(() => {
      document.body.innerHTML = `
<form>
  <div class="typeahead__container">
    <div class="typeahead__field">
        <div class="typeahead__query">
           <input class="js-typeahead" />
        </div>
    </div>
  </div>
</form>`;

      myTypeahead = $.typeahead({
        input: ".js-typeahead",
        minLength: 0,
        searchOnFocus: true,
        source: {
          data: ["data1", "data2", "data3"]
        }
      });
    });

    it("Should display results when the Typeahead input is focused", done => {
      expect(myTypeahead.result).toEqual([]);

      myTypeahead.node.triggerHandler("focus").done(() => {
        expect(myTypeahead.source).toEqual({
          group: [
            { display: "data1", group: "group" },
            { display: "data2", group: "group" },
            { display: "data3", group: "group" }
          ]
        });

        expect(myTypeahead.result.length).toEqual(3);
        expect(myTypeahead.container.hasClass("result")).toBeTruthy();

        done();
      });
    });
  });

  describe("Typeahead searchOnFocus is defined, the source should not be searched when focused because of minLength: 2", () => {
    beforeAll(() => {
      document.body.innerHTML = `
<form>
  <div class="typeahead__container">
    <div class="typeahead__field">
        <div class="typeahead__query">
           <input class="js-typeahead" />
        </div>
    </div>
  </div>
</form>`;

      myTypeahead = $.typeahead({
        input: ".js-typeahead",
        searchOnFocus: true,
        dynamic: true,
        source: {
          data: ["data1", "data2", "data3"]
        }
      });
    });

    it("Should not display results when the Typeahead input is focused", done => {
      expect(myTypeahead.result).toEqual([]);

      myTypeahead.node.triggerHandler("focus").done(() => {
        expect(myTypeahead.result).toEqual([]);
        expect(myTypeahead.container.hasClass("result")).toBeFalsy();

        myTypeahead.node
          .val("da")
          .triggerHandler("focus")
          .done(() => {
            expect(myTypeahead.container.hasClass("result")).toBeTruthy();
            done();
          });
      });
    });
  });
});
