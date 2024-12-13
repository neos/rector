#!/usr/bin/env bash

export FUSION_TARGETPATH=src/Core/FusionProcessing/Fusion
export AFX_PARSER_TARGETPATH=src/Core/FusionProcessing/AfxParser

if [[ "$OSTYPE" == "darwin"* ]]; then
  SEDOPTION="-i ''"
else
  SEDOPTION="-i "
fi

# To create the patch file for Neos.Fusion.Afx, do:
# cd Packages/Neos
# patch -p1 < ../Libraries/neos/rector/scripts/fusion-object-parser.patch
## Now, do your modifications as needed.
# git diff -- Neos.Fusion/ > ../../rector/scripts/fusion-object-parser.patch
# git restore -- Neos.Fusion/

rm -Rf $FUSION_TARGETPATH
cp -R ../Packages/Neos/Neos.Fusion/Classes/Core $FUSION_TARGETPATH
pushd $FUSION_TARGETPATH
patch -p4 < ../../../../scripts/fusion-object-parser.patch
popd
grep -rl 'Neos\\Fusion\\Core' $FUSION_TARGETPATH | xargs sed $SEDOPTION 's/Neos\\Fusion\\Core/Neos\\Rector\\Core\\FusionProcessing\\Fusion/g'

# To create the patch file for Neos.Fusion.Afx, do:
# cd Packages/Neos
# patch -p1 < ../Libraries/neos/rector/scripts/afx-eel-positions.patch
## Now, do your modifications as needed.
# git diff -- Neos.Fusion.Afx/ > ../../rector/scripts/afx-eel-positions.patch
# git restore -- Neos.Fusion.Afx/

rm -Rf $AFX_PARSER_TARGETPATH
cp -R ../Packages/Neos/Neos.Fusion.Afx/Classes/Parser $AFX_PARSER_TARGETPATH
pushd $AFX_PARSER_TARGETPATH
patch -p4 < ../../../../scripts/afx-eel-positions.patch
popd
grep -rl 'Neos\\Fusion\\Afx\\Parser' $AFX_PARSER_TARGETPATH | xargs sed $SEDOPTION 's/Neos\\Fusion\\Afx\\Parser/Neos\\Rector\\Core\\FusionProcessing\\AfxParser/g'
