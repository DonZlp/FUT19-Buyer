$(document).ready(function() {

    function get_level_display(rating){
        if(rating < 65) {
            return 'bronze';
        }else if(rating > 65 && rating < 75) {
            return 'silver';
        }else{
            return 'gold';
        }
    }

    function get_revision_display(rare_type) {
        if (rare_type == 3) {
            return "if"
        } else if (rare_type == 4) {
            return "purple"
        } else if (rare_type == 5) {
            return "toty"
        } else if (rare_type == 6) {
            return "bluered"
        } else if (rare_type == 7) {
            return "stpatrick"
        } else if (rare_type == 8) {
            return "motm"
        } else if (rare_type == 9) {
            return "pink"
        } else if (rare_type == 10) {
            return "pro"
        } else if (rare_type == 11) {
            return "tots"
        } else if (rare_type == 12) {
            return "legend"
        } else if (rare_type == 13) {
            return "wcmode_blue"
        } else if (rare_type == 14) {
            return "unicef"
        } else if (rare_type == 15) {
            return "imotm"
        } else if (rare_type == 16) {
            return "futties-winners"
        } else if (rare_type == 17) {
            return "storymode"
        } else if (rare_type == 18) {
            return "fut-champ"
        } else if (rare_type == 19) {
            return "confmotm"
        } else if (rare_type == 20) {
            return "newimotm"
        } else if (rare_type == 21) {
            return "otw"
        } else if (rare_type == 22) {
            return "halloween"
        } else if (rare_type == 23) {
            return "movember"
        } else if (rare_type == 24) {
            return "sbc"
        } else if (rare_type == 25) {
            return "sbc_premium"
        } else if (rare_type == 26) {
            return "tott"
        } else if (rare_type == 27) {
            return "white-blue-mls"
        } else if (rare_type == 28) {
            return "award-winner"
        } else if (rare_type == 30) {
            return "fut-bd"
        } else if (rare_type == 31) {
            return "united"
        } else if (rare_type == 32) {
            return "futmas"
        } else if (rare_type == 33) {
            return "rtr_selected"
        } else if (rare_type == 34) {
            return "ptgs"
        } else if (rare_type == 35) {
            return "fof"
        } else if (rare_type == 36) {
            return "marquee"
        } else if (rare_type == 37) {
            return "championship"
        } else if (rare_type == 38) {
            return "motm_eu"
        } else if (rare_type == 40) {
            return "rrc"
        } else if (rare_type == 41) {
            return "rrr"
        } else if (rare_type == 42) {
            return "potm_bundesliga"
        } else if (rare_type == 43) {
            return "potm_epl"
        } else if (rare_type == 47) {
            return "ucl_non_rare"
        } else if (rare_type == 48) {
            return "ucl_rare"
        } else if (rare_type == 49) {
            return "ucl_motm"
        } else if (rare_type == 50) {
            return "ucl_live"
        } else if (rare_type == 51) {
            return "sbc_flashback"
        } else if (rare_type == 52) {
            return "swap_deals_1"
        } else if (rare_type == 53) {
            return "swap_deals_2"
        } else if (rare_type == 54) {
            return "swap_deals_3"
        } else if (rare_type == 55) {
            return "swap_deals_4"
        } else if (rare_type == 56) {
            return "swap_deals_5"
        } else if (rare_type == 57) {
            return "swap_deals_6"
        } else if (rare_type == 58) {
            return "swap_deals_7"
        } else if (rare_type == 59) {
            return "swap_deals_8"
        } else if (rare_type == 60) {
            return "swap_deals_9"
        } else if (rare_type == 61) {
            return "swap_deals_10"
        } else if (rare_type == 62) {
            return "swap_deals_11"
        } else if (rare_type == 63) {
            return "swap_deals_rewards"
        } else if (rare_type == 68) {
            return "europa_tott"
        } else if (rare_type == 69) {
            return "sbc_ucl"
        } else if (rare_type == 70) {
            return "ucl_tott"
        } else if (rare_type == 78) {
            return "europa_base"
        } else {
            return ""
        }
    }

    if(document.getElementsByName("player_search").length > 0) {
        futsearch = $('#player_search');
        futsearch.focus();
        futsearch.keydown(function () {
            futsearch.css('background-image', 'None');
        });
        futsearch.autocomplete({
            autoFocus: true,
            minLength: 3,
            delay: 500,
            source: '/admin/players/find',
            select: function (event, ui) {
                $( "input[name='futbin_id']").val(ui.item.futbin_id);
                $( "input[name='base_id']").val(ui.item.base_id);
                $( "input[name='resource_id']").val(ui.item.resource_id);
                $( "input[name='name']").val(ui.item.short_name);
                $( "input[name='position']").val(ui.item.position);
                $( "input[name='rating']").val(ui.item.rating);
                $( "input[name='league_id']").val(ui.item.league);
                $( "input[name='club_id']").val(ui.item.club);
                $( "input[name='nation_id']").val(ui.item.nation);
                $( "input[name='player_search']").val(ui.item.short_name);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            $(ul).addClass("quicksearch short");
            var rating_color = get_level_display(item.rating);
            var revision = item.revision_type ? item.revision_type.toLowerCase() : '';
            return $("<li></li>").data("item.autocomplete", item).append('<a data-id="' + item.id + '" data-slug="' + item.slug + '"><img class="clubpicture" src="http://cdn.futbin.com/content/fifa19/img/clubs/' + item.club + '.png" /><img class="nationpicture" src="http://cdn.futbin.com/content/fifa19/img/nation/' + item.nation + '.png" /><span class="name">' + item.short_name + '</span> (' + item.position + ') <span class="rating rating-search ut19 ' + rating_color + ' ' + get_revision_display(revision) + '">' + item.rating + '</span></a>').appendTo(ul);
        };
    }

});