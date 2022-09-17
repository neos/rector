#!/usr/bin/env bash

export FUSION_PARSER_TARGETPATH=src/Core/FusionProcessing/FusionParser
export AFX_PARSER_TARGETPATH=src/Core/FusionProcessing/AfxParser

rm -Rf $FUSION_PARSER_TARGETPATH
cp -R ../../../Neos/Neos.Fusion/Classes/Core/ObjectTreeParser $FUSION_PARSER_TARGETPATH
grep -rl 'Neos\\Fusion\\Core\\ObjectTreeParser' $FUSION_PARSER_TARGETPATH | xargs sed -i '' 's/Neos\\Fusion\\Core\\ObjectTreeParser/Neos\\Rector\\Core\\FusionProcessing\\FusionParser/g'


# To create the patch file for Neos.Fusion.Afx, do:
# cd Packages/Neos
# patch -p1 < ../Libraries/neos/rector/scripts/afx-eel-positions.patch
## Now, do your modifications as needed.
# git diff -- Neos.Fusion.Afx/ > ../Libraries/neos/rector/scripts/afx-eel-positions.patch
# git restore -- Neos.Fusion.Afx/

rm -Rf $AFX_PARSER_TARGETPATH
cp -R ../../../Neos/Neos.Fusion.Afx/Classes/Parser $AFX_PARSER_TARGETPATH
pushd $AFX_PARSER_TARGETPATH
patch -p4 < ../../../../scripts/afx-eel-positions.patch
popd
grep -rl 'Neos\\Fusion\\Afx\\Parser' $AFX_PARSER_TARGETPATH | xargs sed -i '' 's/Neos\\Fusion\\Afx\\Parser/Neos\\Rector\\Core\\FusionProcessing\\AfxParser/g'
